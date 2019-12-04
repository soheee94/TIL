[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 1~2. 컴포넌트 만들기

[TodoList예제](https://hp21n.csb.app/)

## 만들어야 할 컴포넌트 확인하기

1. Todo Template
2. Todo Head
3. Todo List
4. Todo Item
5. Todo Create

### 페이지에 배경 색상 적용

페이지의 배경 색상을 설정하려면 `body` 태그에 CSS 를 적용해주면 되는데요, 이를 하기 위해서는 index.css 에서 해도 무방하지만, 만약에 styled-components 를 사용해서 적용을 하고 싶을땐 어떻게 할 수 있는지 알아봅시다.

styled-components 에서 특정 컴포넌트를 만들어서 스타일링 하는게 아니라 글로벌 스타일을 추가하고 싶을 땐 [`createGlobalStyle`](https://www.styled-components.com/docs/api#createglobalstyle) 이라는 것을 사용합니다. 이 함수를 사용하면 컴포넌트가 만들어지는데, 이 컴포넌트를 렌더링하면 됩니다.

```javascript
import React from "react";
import { createGlobalStyle } from "styled-components";

const GlobalStyle = createGlobalStyle`
  body {
    background: #e9ecef;
  }
`;

function App() {
  return (
    <>
      <GlobalStyle />
      <div>안녕하세요</div>
    </>
  );
}

export default App;
```

공식 홈페이지 예제

```javascript
import { createGlobalStyle } from 'styled-components'

// props 사용하여 변경
const GlobalStyle = createGlobalStyle`
  body {
    color: ${props => (props.whiteColor ? 'white' : 'black')};
  }
`

// later in your app

<React.Fragment>
  <GlobalStyle whiteColor />
  <Navigation /> {/* example of other top-level stuff */}
</React.Fragment>
```

## TodoTemplate

## TodoHead

## TodoList
