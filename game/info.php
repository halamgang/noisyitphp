<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <title>Developer_INFO</title>
  <style>
html, body, section {
  block-size: 100%;
}

html {
  scroll-snap-type: y mandatory;
}

section {
  scroll-snap-align: start;
  scroll-snap-stop: always;

  display: flex;
  align-items: center;
  justify-content: center;
}
section {
  min-height: 100vh; /* Set a minimum height for each section */
  display: flex; 
  align-items: center; /* Center content vertically */
  justify-content: center; /* Center content horizontally */
}
section:nth-of-type(even) {
  color: white;
  background: black;
}

section:nth-of-type(odd) {
  color: black;
  background: white;
}

body {
  margin: 0;
  font-family: system-ui, sans-serif;
  font-size: 4rem;
}
}
#app{
  text-align:center;
  font-size:50px;
  background-image: linear-gradient(
    -225deg,
    #231557 0%,
    #44107a 29%,
    #ff1361 67%,
    #fff800 100%
  );
  background-clip:border-box;
   text-fill-color: transparent;
  background-clip:text;
  -webkit-background-clip:text;
  -webkit-text-fill-color: transparent;
  text-fill-color: transparent;
  animation: textclip 2s linear infinite;
}

@keyframes textclip {
  to {
    background-position: 200% center;
  }
}

.container .welcome {
    font-family: sans-serif;
    text-transform: uppercase;
    display: block;
}

.container {
    text-align: center;
    /* position removed */
    width: 100%;
}



.welcome {
    font-size: 60px;
    font-weight: 700;
    letter-spacing: 8px;
    margin-bottom: 20px;
    background-image: linear-gradient(
      -225deg,
      #231557 0%,
      #44107a 29%,
      #ff1361 67%,
      #fff800 100%
    );
    background-clip:border-box;
     text-fill-color: transparent;
    background-clip:text;
    -webkit-background-clip:text;
    -webkit-text-fill-color: transparent;
    text-fill-color: transparent;
    animation: textclip 2s linear infinite, text 3s 1; /* Add your existing 'text' animation here */
}

@keyframes textclip {
   to {
     background-position: 200% center;
   }
   animation-iteration-count: infinite; /* 애니메이션을 무한히 반복하도록 설정 */
}


.muzile {
    font-size: 30px;
    color: #ffffff; /* or 'white' */
    background-image: linear-gradient(
      -225deg,
      #231557 0%,
      #44107a 29%,
      #ff1361 67%,
      #fff800 100%
    );
    background-clip:border-box;
     text-fill-color: transparent;
    background-clip:text;
    -webkit-background-clip:text;
    -webkit-text-fill-color: transparent;
    text-fill-color: transparent;
    animation: textclip 2s linear infinite; 
}

@keyframes text {
    0%{
        color: #000;
        margin-bottom: -40px;
    }

    30%{
        letter-spacing: 25px;
        margin-bottom: -40px;
    }

    85%{
        letter-spacing: 8px;
        margin-bottom: -40px;
    }
}
        @media (max-width: 768px) {
            body {
                font-size: 2rem; /* 폰트 크기를 모바일에 맞게 조정 */
            }

            section {
                min-height: 50vh; /* 섹션 높이를 모바일에 맞게 조정 */
            }
        }
  </style>
</head>
<body>

<section>
  <div id="app"></div>
</section>
<section>
  <div class="container">
      <span class="welcome">Portfolio </span> 
      <span class="muzile">- 구기대회 기반 투표 이벤트</span><br>
      <span class="muzile">- 서라벌 급식 설문조사</span>
  </div>
</section>
<section>
  <div id="app1"></div>
</section>
<section>
  <div class="container">
      <span class="welcome">BackEnd & Server</span> 
      <span class="muzile">- 프로젝트 매니저 & 서버 및 프론트 | 강하람</span><br>
      <span class="muzile">- 웹서비스 관리자 & 개인정보 책임 | 이종우</span>
  </div>
</section>
<section>
  <div class="container">
      <span class="welcome">FrontEnd & Graphics Art</span> 
      <span class="muzile">- 디자인 책임 & 서버 및 프론트 | 박민찬</span><br>
      <span class="muzile">- 클라이먼트 서버 통신 | 김재영</span>
  </div>
</section>
<section>
  <div class="container">
      <span class="welcome">Support </span> 
      <span class="muzile">- 커뮤니케이션 및 Noisy IT 부장 | 허윤호</span><br>
      <span class="muzile">- 문서 작성 및 서포트 책임 | 이동준</span><br>
      <span class="muzile">- 빅데이터 수집 및 분석 | 김태윤</span><br>
      <span class="muzile">- 1학년부 홍보 | 오명훈</span>
  </div>
</section>
<section>
  <div class="container">
      <span class="welcome">Noisy IT 2023 </span> 
      <span class="muzile">© 2023 Noisy IT. All rights reserved.</span><br>
      <span class="muzile">Web Development Division & "SORABOL" High School</span>
  </div>
</section>
<script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
<script>
var app = document.getElementById('app');

var typewriter = new Typewriter(app, {
    loop: true
});

typewriter.typeString('스크롤을 내려 더 많은 정보를 보세요!')
    .pauseFor(2500)
    .deleteAll()
    .typeString('Developer Info')
    .pauseFor(2500)
    .deleteAll()
    .typeString('<strong>Noisy IT Webb</strong>')
    .pauseFor(2500)
    .start();

</script>
<script>
var app1 = document.getElementById('app1');

var typewriter = new Typewriter(app1, {
    loop: true
});

typewriter.typeString('Developer?')
    .pauseFor(2500)
    .deleteAll()
    .typeString('Who created this website?')
    .pauseFor(2500)
    .deleteAll()
    .typeString('<strong>Noisy IT webb 2023</strong>')
    .pauseFor(2500)
    .start();

</script>
</body>
</html>