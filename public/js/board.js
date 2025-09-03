const uploadedImages = [];
const initialContent = $('#editor').data('content') || '';

const editor = new toastui.Editor({
  el: document.querySelector('#editor'),
  height: '400px',
  initialEditType: 'wysiwyg',
  previewStyle: 'vertical',
  initialValue: initialContent,
  hooks: {
    addImageBlobHook: async (blob, callback) => {
      const formData = new FormData();
      formData.append('image', blob);
      formData.append('type', 'tmp');
      const res = await fetch('/api/uploads', {
        method: 'POST',
        body: formData
      });

      const result = await res.json();
      uploadedImages.push(result.url);
      callback(result.url, 'image');
    }
  }
});
$(document).ready(function () {
    
    
    $('#boardForm').on('submit', function (e) {

        e.preventDefault(); 

        $('#content').val(editor.getHTML());

        const subject = $('#subject').val().trim();
        const content = editor.getMarkdown().trim();

        if (subject === '') {
            alertModal($('#msg_input_title').data('label'));
            return;
        }

        if (content === '') {
            alertModal($('#msg_input_contents').data('label'));
            return;
        }
    
        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                alertModal(response.message, response.url);
            },
            error: function(xhr, status, error) {
                console.log(error);
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';

                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            errorMessage += errors[field].join('<br>');
                        }
                    }

                    alertModal(errorMessage.trim());
                } else {
                    alertModal(errornotice);
                }
            }
        });
    });
    
});