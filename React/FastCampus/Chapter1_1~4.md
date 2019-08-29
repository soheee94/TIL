[ FastCampus 강의 요약 노트 ]
https://react.vlpt.us/

# 01. 리액트는 어쩌다 만들어졌을까?

DOM을 변형시키기 위해서 JS에서 특정 DOM을 선택한 뒤, 특정 이벤트가 발생하면 변화를 주도록 설정해야한다.
만약에 인터랙션이 자주 발생하고, 이에 따라 동적으로 UI를 표현해야 한다면 규칙이 다양해지고 그러면 관리/유지보수 하기가 힘들어진다.

그래서 Ember, Backbone, AngularJS등의 프레임워크가 만들어졌다. 자바스크립트의 특정 값이 바뀌면 특정 DOM의 속성이 바뀌도록 연결해주어서, 업데이트 하는 작업을 간소화해주는 방식이다.

리액트는 DOM을 어떻게 업데이트할 지 규칙을 정하는 것이 아닌, 아예 다 날려버리고 처음부터 모든걸 새로 만들어서 보여준다. 하지만, 모든걸 다 날려버리고 모든걸 새로 만들게 된다면, 속도가 굉장히 느릴 것이다. 그래서 리액트는 **Virtual DOM**이라는 것을 사용해서 가능하게 했다.

Virtaul DOM은 가상의 DOM이다. 메모리에 가상으로 존재하는 DOM으로서 JS객체이기 때문에 작동 성능이 실제로 브라우저에서 DOM을 보여주는 것보다 속도가 훨씬 빠르다.


# 02. 작업환경 준비

## 설치사항
* Node.js
* Yarn
* VS Code
* Git bash

## 새 프로젝트 만들기
``` npx create-react-app begin-react ```  
begin react라는 디렉토리가 생기고 그 안에 리액트 프로젝트가 생성된다.  

> **주의**  
> 첫번째 줄의 'npx'는 npm 5.2+ 버전의 패키지 실행 도구  
> create-react-app 은 react 배우기에 간편한 환경 (SPA)


# 03. 나의 첫번째 리액트 컴포넌트

리액트 컴포넌트를 만들 땐

```javascript
import React from 'react'; 
```

를 통하여 리액트를 불러와주어야 한다. 리액트 컴포넌트는 함수형태로 작성할 수도 있고 클래스 형태로도 작성할 수 있다. 리액트 컴포넌트에서는 XML 형식의 값을 반환해 줄 수 있는데 이를 **JSX** 라고 부른다.

```javascript
export default Hello; 
```  

이 코드는 Hello 라는 컴포넌트를 내보내겠다는 의미이다.

**App.js** 에서 

```javascript
import Hello from './Hello'; 
```

를 통해 Hello 컴포넌트를 불러온다. 컴포넌트는 일종의 <U>UI조각</U> 이고, 쉽게 재사용이 가능하다.

**index.js** 를 열어보면

```javascript
ReactDOM.render(<App />, document.getElementById('root')); 
```

이런코드가 보일 것이다.
여기서 ReactDOM.render 의 역할은 브라우저에 있는 실제 DOM 내부에 리액트 컴포넌트를 렌더링하겠다는 것을 의미한다.

결국 리액트 컴포넌트가 렌더링 될 때에는, 렌더링 된 결과물이 'root' id를 가지고 있는 DIV 내부에 렌더링되는 것이다.

# 04. JSX의 기본 규칙 알아보기

JSX는 리액트에서 생김새를 정의할 때, 사용하는 문법이다.  

```javascript
 return <div>안녕하세요</div>; 
```

리액트 컴포넌트 파일에서 XML형태로 코드를 작성하면 **babel**이 JSX를 JS로 변화을 해준다.
Babel은 자바스크립트의 문법을 확장해주는 도구이다. 아직 지원하지 않는 최신 문법이나, 편의상 사용하거나 실험적인 자바스크립트 문법들을 정식 자바스크립트 형태로 변환해줌으로서 구형 브라우저 같은 환경에서도 제대로 실행할 수 있게 해주는 역할을 한다.

## JSX 문법 규칙

### 꼭 닫혀야하는 태그

```javascript
return (
    <div>
      <Hello />
      <Hello />
      <Hello />
      <div>
    </div>
  ); 
```

다음과 같은 코드는 오류가 발생한다.
태그를 열었으면 꼭, ```<div></div>``` 이렇게 닫아주어야한다.

```input``` 또는 ```br``` 태그를 사용할 때 닫지 않고 사용하기도 한다.
그러나! 리액트에서는 그렇게 하면 안된다.

태그와 태그 사이에 내용이 들어가지 않을 때는, **Self Closing**태그 라는 것을 사용해야 한다. Hello 컴포넌트를 사용할 때도 이 태그를 사용 했다. 열리고 바로 닫히는 태그를 의미한다.

```javascript
 return (
    <div>
      <Hello />
      <Hello />
      <Hello />
      <input />
      <br />
    </div>
  );
```

### 꼭 감싸져야 하는 태그

두 개 이상의 태그는 무조건 하나의 태그로 감싸져있어야 한다.
```javascript
function App() {
  return (
    <Hello />
    <div>안녕히계세요.</div>
  );
}
```
이 코드는 에러 발생!

```javascript
  return (
    <div>
      <Hello />
      <div>안녕히계세요</div>
    </div>
  );
```
이렇게 감싸주면 해결 된다. 그러나 불필요한 div로 감싸는게 좋지 않은 상황이라면 리액트의 **Fragment**라는 것을 사용하면 된다.
```javascript
return (
    <>
      <Hello />
      <div>{name}</div>
    </>
  );
```

## style과 className
JSX에서 스타일을 설정하는 방법은 HTML 설정 방법과 다르다.
1. 인라인 스타일은 객체 형태로 작성
2. ```background-color``` 처럼 -로 구분되어 있는 이름들은 ```backgroundColor```처럼 camelCase형태로 네이밍
3. CSS class를 설정할 때에는 ```class=```가 아닌 ```className=```로 설정

```javascript
import './App.css';

function App() {
  const name = 'react';
  const style = {
    backgroundColor: 'black',
    color: 'aqua',
    fontSize: 24, // 기본 단위 px
    padding: '1rem' // 다른 단위 사용 시 문자열로 설정
  }

  return (
    <>
      <Hello />
      <div style={style}>{name}</div>
      <div className="gray-box"></div>
    </>
  );
}
```

## 주석
```javascript
 return (
    <>
      {/* 주석은 화면에 보이지 않습니다 */}
      /* 중괄호로 감싸지 않으면 화면에 보입니다 */
      <Hello 
       // 열리는 태그 내부에서는 이렇게 주석을 작성 할 수 있습니다.
      />
      <div style={style}>{name}</div>
      <div className="gray-box"></div>
    </>
  );
```