function daumPostcode() {
    new daum.Postcode({
        oncomplete: function(data) {
          
            let addr = '';
            
            if (data.userSelectedType === 'R') {
                addr = data.roadAddress;
            } else { 
                addr = data.jibunAddress;
            }

            document.getElementById('postcode').value = data.zonecode;
            document.getElementById("address").value = addr;

            document.getElementById("detailAddress").focus();
        }
    }).open();
}