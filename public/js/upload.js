export function upload($fileInput, $defaultContent, $imagePreview, $uploadBox)
{
    $(document).ready(function() {
     
        $fileInput.change(function() {
            const file = this.files[0];
            
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('파일 크기는 5MB를 초과할 수 없습니다.');
                    resetUpload();
                    return;
                }
                
                if (!file.type.match('image/(jpeg|jpg|png)')) {
                    alert('jpg, jpeg, png 파일만 업로드 가능합니다.');
                    resetUpload();
                    return;
                }
           
                const reader = new FileReader();
                reader.onload = function(e) {
                    $imagePreview.attr('src', e.target.result).removeClass('d-none');
                    $defaultContent.addClass('d-none');
                    $uploadBox.addClass('p-2');
                }
                reader.readAsDataURL(file);
            }
        });
    
        function resetUpload() {
            $fileInput.val('');
            $defaultContent.removeClass('d-none');
            $imagePreview.addClass('d-none');
            $uploadBox.removeClass('p-2');
        }
    });
}

