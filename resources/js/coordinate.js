function getcoordinate() {
    navigator.geolocation.getCurrentPosition(request);
}

function request(position) {
    fetch('/home', { // 第1引数に送り先
        method: 'POST', // メソッド指定
        headers: { 'Content-Type': 'application/json' }, // jsonを指定
        body: JSON.stringify(param) // json形式に変換して添付
    })
    .then(response => response.json());
}