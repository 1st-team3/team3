function readURL(input) {
    if ( ( input.files[0] != null) ) {
        var reader = new FileReader(); // FileReader 파일을 임시로 읽어줌
        reader.onload = function(e) { //reader. 파일을 읽을경우 onload  데이터를 이미지로 변환해서 보여줌
            document.getElementById('preview').src = e.target.result;
            // 파일이 선택됐을 때만 기존 이미지를 숨깁니다.
            document.getElementById('existing_image').style.display = "none";
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        document.getElementById('preview').src = "";
    }
}