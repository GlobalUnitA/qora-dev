<?php

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use App\Models\LanguagePolicy;
use App\Models\MessageKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{

    private array $locale;

    public function __construct()
    {
        $this->locale = $this->getLocale();
    }
   
    public function index(Request $request, $mode)
    {

        switch ($mode) {

            case 'default' :

                $locale = LanguagePolicy::where('type', 'locale')->first()->content;
                $category = LanguagePolicy::where('type', 'category')->first()->content;

                return view('admin.language.default', compact('locale', 'category'));    

            break;

            case 'message' :

                $category_list = LanguagePolicy::where('type', 'category')->first()->content;
                $selected_category = $request->category ?? 'system';

                $message_key = MessageKey::orderBy('id')->where('category', $selected_category)->select(['key', 'description'])->get();
                
                $data = [
                    'category_list' => $category_list,
                    'selected_category' => $selected_category,
                    'message_key' => $message_key,
                ];
                
                return view('admin.language.message', $data);

            break;
        
            case 'language' :

                $category_list = LanguagePolicy::where('type', 'category')->first()->content;
                $selected_category = $request->category ?? 'system';
                
                $message_key = MessageKey::where('category', $selected_category)->get();
                $message = [];

                foreach ($this->locale as $locale) {
                    $file_path = resource_path("lang/{$locale}/{$selected_category}.php");

                  
                    if (File::exists($file_path)) {
                        $messages = include $file_path;
                        
                        foreach ($message_key as $key => $value) {
                            $message[$key]['key'] = $value['key'];
                            $message[$key]['desc'] = $value['description'];
                            $message[$key]['value'][$locale] = $messages[$value['key']] ?? '';
                        }
                    } else {
                        foreach ($message_key as $key => $value) {
                            $message[$key]['key'] = $value['key'];
                            $message[$key]['desc'] = $value['description'];
                            $message[$key]['value'][$locale] = '';
                        }
                    }
                }
               
                
                $data = [
                    'category_list' => $category_list,
                    'selected_category' => $selected_category,
                    'message' => $message,
                ];

                return view('admin.language.language', $data);

            break;

        }
    }

    public function update(Request $request)
    {
        $mode = $request->mode;
       
        switch($mode) {
            case 'default' :

                $data = $request->input('content', []);

                $locales = array_filter($data['locale'] ?? [], function ($locale) {
                    return !empty($locale['code']) && !empty($locale['name']);
                });
                $locales = array_values($locales);

                $categories = array_filter($data['category'] ?? [], function ($category) {
                    return !is_null($category) && trim($category) !== '';
                });
                $categories = array_values($categories);
              
                try {
                    DB::beginTransaction();
                    
                    LanguagePolicy::updateOrCreate(
                        ['type' => 'locale'],
                        ['content' => $locales]
                    );

                    LanguagePolicy::updateOrCreate(
                        ['type' => 'category'],
                        ['content' => $categories]
                    );
                        
                    DB::commit();
        
                    return response()->json([
                        'status' => 'success',
                        'message' => '설정이 완료되었습니다.',
                        'url' => route('admin.language', ['mode' => 'default']),
                    ]);
        
                } catch (\Exception $e) {
                    DB::rollBack();
        
                    \Log::error('Failed to update policy', ['error' => $e->getMessage()]);
        
                    return response()->json([
                        'status' => 'error',
                        'message' => '예기치 못한 오류가 발생했습니다.',
                    ]);
                }

            break;

            case 'message' :

                $category = $request->category;
                $keys = $request->input('key', []);
                $descriptions = $request->input('description', []);

                if (count($keys) !== count($descriptions)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => '메시지 키가 존재하지 않습니다.',
                    ]);
                }
                try {
                    DB::beginTransaction();
            
                    foreach ($keys as $index => $key) {
            
                        if (empty($key)) {
                            continue;
                        }

                        $description = $descriptions[$index];
                    
                        MessageKey::create([
                            'key' => $key,
                            'category' => $category, 
                            'description' => $description,
                        ]);
                    }

                    DB::commit();
                
                    return response()->json([
                        'status' => 'success',
                        'message' => '메시지가 설정되었습니다.',
                        'url' => route('admin.language', ['mode' => 'message', 'category' => $category]),
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
        
                    \Log::error('Failed to create Message Key', ['error' => $e->getMessage()]);
        
                    return response()->json([
                        'status' => 'error',
                        'message' => '예기치 못한 오류가 발생했습니다.',
                    ]);
                }
        

            break;

            case 'language' :
               
                $languages = $request->input('lang', []);
                $category = $request->category;

                foreach ($this->locale as $locale) {    
                    $file_path = resource_path("lang/{$locale}/{$category}.php");

                    if (!File::exists($file_path)) {
                        $localized_messages = [];
                    } else {
                        try {
                            $localized_messages = include $file_path;
                            if (!is_array($localized_messages)) {
                                $localized_messages = [];
                            }
                        } catch (\Exception $e) {
                            $localized_messages = [];
                        }
                    }

                    foreach ($languages as $key => $translations) {    
                        $localized_messages[$key] = $translations[$locale] ?? '';
                    }
                
                    $content = "<?php\n\nreturn " . var_export($localized_messages, true) . ";\n";
                    File::put($file_path, $content);
                }

                clearstatcache();
                sleep(1);
            
              
                return response()->json([
                    'status' => 'success',
                    'message' => '언어 설정이 완료되었습니다.',
                    'url' => route('admin.language', ['mode' => 'language', 'category' => $category]),
                ]);

            break;
        }
        
    }

    public function delete(Request $request)
    {

        $page = $request->page;
        $key = $request->key;

        try {
          
            DB::beginTransaction();

            MessageKey::where('key', $request->key)->delete();

            DB::commit();

            foreach ($this->locale as $locale) {    

                $file_path = resource_path("lang/{$locale}/messages.php");

                if (!File::exists($file_path)) {
                    continue;
                }

                $localized_messages = include $file_path;

                if (isset($localized_messages[$page][$key])) {
                    unset($localized_messages[$page][$key]);
                }

                $content = "<?php\n\nreturn " . var_export($localized_messages, true) . ";\n";
                File::put($file_path, $content);
            }

            return response()->json([
                'status' => 'success',
                'message' => '삭제되었습니다.',
                'url' => route('admin.language', ['mode' => 'message', 'category' => $category]),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '삭제 중 오류가 발생했습니다.',
            ]);
        }
    }
    

    public function changeLanguage($locale)
    {

        if (in_array($locale, $this->locale)) {
            Session::put('app_locale', $locale);
        }

        return redirect()->back();
    }

    private function getLocale()
    {
        
        $languages = LanguagePolicy::where('type', 'locale')->first()->content;

        foreach ($languages as $key => $val) {
            $data[] = $val['code'];
        }

        if (!isset($data)) {
            return [];
        }

        return $data;
    }
    
}
