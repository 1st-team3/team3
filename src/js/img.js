function readURL(input) { // input에는 this가 담김
    if (input.files && input.files[0]) { // input(fome)에 있던 요소중 파일을 체크함 
        var reader = new FileReader(); // 객체 생성
        reader.readAsDataURL(input.files[0]); // readAsDataURL 메소드 사용 : 파일 정보를 URL로 읽어옴
        reader.onload = function(e) { // onload : 성공적으로 파일을 읽을경우 호출할 함수를 지정 / filereader가 사용 할 준비가 됐을 때
            document.getElementById('preview').src = e.target.result; //e.target = 이벤트가 발생한 요소를 가져옴 / result = FileReader 은 읽은 요소(URL)를 result에 담음  
            document.getElementById('existing_image').style.display = "none"; // get으로 받은 이미지를 출력하는 라인에 style.display = "none" 을 추가해서 기존 이미지를 숨김
        };
    }
    else {
        document.getElementById('preview').src = "";
    }
}