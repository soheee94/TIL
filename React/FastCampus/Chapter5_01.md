[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 1. 프로젝트 준비 및 기본적인 사용법

라이브러리 설치

```cmd
$ yarn add react-router-dom
```

## 프로젝트에 라우터 적용

```javascript
// index.js
import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import App from "./App";
import * as serviceWorker from "./serviceWorker";
import { BrowserRouter } from "react-router-dom"; // browsuerRouter 불러오기

ReactDOM.render(
  // app을 browserRouter로 감싸기!
  <BrowserRouter>
    <App />
  </BrowserRouter>,
  document.getElementById("root")
);
```

## 페이지 만들기

라우트로 사용 할 페이지 컴포넌트 만들기

```javascript
// Home.js
import React from "react";

function Home() {
  return (
    <div>
      <h1> 여기는 홈 입니당 </h1>
    </div>
  );
}

export default Home;

// About.js
import React from "react";

function About() {
  return (
    <div>
      <h1> 소개 페이지 </h1>
    </div>
  );
}

export default About;
```

## Route: 특정 주소에 컴포넌트 연결하기

`Route` 컴포넌트 사용하기

```javascript
<Route path="주소규칙" component={보여주고싶은 컴포넌트}>
```

```javascript
// App.js
import React from "react";
import { Route } from "react-router-dom";
import About from "./About";
import Home from "./Home";

const App = () => {
  return (
    <div>
      <Route path="/" component={Home} />
      <Route path="/about" component={About} />
    </div>
  );
};

export default App;
```

여기에서 `/` 경로로 들어가면 홈 컴포넌트가 뜨고, `/about` 경로로 들어가면 두 컴포넌트가 모두 보여진다.

이는 `/about` 경로가 `/` 규칙과도 일치하기 때문에 발생한 현상인데요, 이를 고치기 위해선 Home 을 위한 라우트에 `exact` 라는 props 를 true 로 설정하시면 됩니다.

```javascript
<Route path="/" exact={true} component={Home} />
// or
<Route path="/" exact component={Home} />
```

이렇게 하면 경로가 완벽히 똑같을 때만 컴포넌트를 보여주게 되어 이슈가 해결된다.

## Link : 누르면 다른 주소로 이동시키기

Link 컴포넌트는 클릭하면 다른 주소로 이동시키는 컴포넌트입니다. 리액트 라우터를 사용할땐 일반 <a href="...">...</a> 태그를 사용하시면 안됩니다. 만약에 하신다면 onClick 에 e.preventDefault() 를 호출하고 따로 자바스크립트로 주소를 변환시켜주어야 합니다.

그 대신에 Link 라는 컴포넌트를 사용해야하는데요, 그 이유는 a 태그의 기본적인 속성은 페이지를 이동시키면서, <mark>페이지를 아예 새로 불러오게됩니다.</mark> 그렇게 되면서 우리 리액트 앱이 지니고있는 상태들도 초기화되고, 렌더링된 컴포넌트도 모두 사라지고 새로 렌더링을 하게됩니다. 그렇기 때문에 a 태그 대신에 Link 컴포넌트를 사용하는데요, 이 컴포넌트는 [HTML5 History API](https://developer.mozilla.org/ko/docs/Web/API/History) 를 사용하여 브라우저의 주소만 바꿀뿐 페이지를 새로 불러오지는 않습니다.

```javascript
import React from "react";
import { Route, Link } from "react-router-dom";
import Home from "./Home";
import About from "./About";

function App() {
  return (
    <div>
      <ul>
        <li>
          <Link to="/">홈</Link>
        </li>
        <li>
          <Link to="/about">소개</Link>
        </li>
      </ul>
      <hr />
      <Route path="/" component={Home} exact />
      <Route path="/about" component={About} />
    </div>
  );
}

export default App;
```
