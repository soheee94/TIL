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

## Todo Item

## Todo Create

# Context API 를 활용한 상태 관리

## Context 만들기

`state` 와 `dispatch` 를 Context 통하여 다른 컴포넌트에서 바로 사용 할 수 있게 해줄건데요, 우리는 하나의 Context 를 만들어서 `state` 와 `dispatch` 를 함께 넣어주는 대신에, 두개의 Context 를 만들어서 따로 따로 넣어줄 것입니다. <u>이렇게 하면 `dispatch` 만 필요한 컴포넌트에서 불필요한 렌더링을 방지 할 수 있습니다.</u> 추가적으로, 사용하게 되는 과정에서 더욱 편리하기도 합니다.

> TodoItem.js 를 통해 확인 가능! state를 사용하지 않기 때문에 전체적으로 리렌더링 하지 않는다. dispatch는 변하지 않기 때문에 실제로 변화된 부분만 리렌더링 된다.

## 기능 구현 하기

Context 를 만들어주었으니, 이제 Context 와 연동을 하여 기능을 구현해봅시다. Context 에 있는 state 를 받아와서 렌더링을 하고, 필요한 상황에는 특정 액션을 dispatch 하면 됩니다.

e.preventDefault(); // submit 시 새로고침 방지
