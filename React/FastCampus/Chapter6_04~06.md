[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 4. 리덕스 모듈 만들기

**리덕스 모듈**이란 다음 항목들이 모두 들어있는 자바스크립트 파일을 의미합니다.

- 액션 타입
- 액션 생성함수
- 리듀서

[리덕스 Github 예제프로젝트](https://github.com/reduxjs/redux/tree/master/examples/todos/src)

- actions
  - index.js
- reducers
  - todos.js
  - visibilityFilter.js
  - index.js

위 예제 프로젝트에서는 액션과 리듀서가 서로 다른 파일에 정의 되어 있다! 하지만, 이 코드들이 꼭 분리되어 있을 필요는 없다!

이 프로젝트에서는 리듀서와 액션 관련 코드들을 하나의 파일에 몰아서 작성! > [Ducks 패턴](https://github.com/erikras/ducks-modular-redux)

## counter 모듈 만들기

```javascript
// modules/counter.js

/* 액션 타입 만들기 */
// Ducks 패턴을 따를땐 액션의 이름에 접두사를 넣어주세요.
// 이렇게 하면 다른 모듈과 액션 이름이 중복되는 것을 방지 할 수 있습니다.
const SET_DIFF = "counter/SET_DIFF";
const INCREASE = "counter/INCREASE";
const DECREASE = "counter/DECREASE";

/* 액션 생성함수 만들기 */
// 액션 생성함수를 만들고 export 키워드를 사용해서 내보내주세요.
export const setDiff = diff => ({ type: SET_DIFF, diff });
export const increase = () => ({ type: INCREASE });
export const decrease = () => ({ type: DECREASE });

/* 초기 상태 선언 */
const initialState = {
  number: 0,
  diff: 1
};

/* 리듀서 선언 */
// 리듀서는 export default 로 내보내주세요.
export default function counter(state = initialState, action) {
  switch (action.type) {
    case SET_DIFF:
      return {
        ...state,
        diff: action.diff
      };
    case INCREASE:
      return {
        ...state,
        number: state.number + state.diff
      };
    case DECREASE:
      return {
        ...state,
        number: state.number - state.diff
      };
    default:
      return state;
  }
}
```

## todos 모듈 만들기

```javascript
// modules/todos.js

/* 액션 타입 선언 */
const ADD_TODO = "todos/ADD_TODO";
const TOGGLE_TODO = "todos/TOGGLE_TODO";

/* 액션 생성함수 선언 */
let nextId = 1; // todo 데이터에서 사용 할 고유 id
export const addTodo = text => ({
  type: ADD_TODO,
  todo: {
    id: nextId++, // 새 항목을 추가하고 nextId 값에 1을 더해줍니다.
    text
  }
});
export const toggleTodo = id => ({
  type: TOGGLE_TODO,
  id
});

/* 초기 상태 선언 */
// 리듀서의 초기 상태는 꼭 객체타입일 필요 없습니다.
// 배열이여도 되고, 원시 타입 (숫자, 문자열, 불리언 이여도 상관 없습니다.
const initialState = [
  /* 우리는 다음과 같이 구성된 객체를 이 배열 안에 넣을 것입니다.
  {
    id: 1,
    text: '예시',
    done: false
  } 
  */
];

export default function todos(state = initialState, action) {
  switch (action.type) {
    case ADD_TODO:
      return state.concat(action.todo);
    case TOGGLE_TODO:
      return state.map(
        todo =>
          todo.id === action.id // id 가 일치하면
            ? { ...todo, done: !todo.done } // done 값을 반전시키고
            : todo // 아니라면 그대로 둠
      );
    default:
      return state;
  }
}
```

## 루트 리듀서 만들기

한 프로젝트에 여러개의 리듀서가 있을때는 이를 한 리듀서로 합쳐서 사용합니다. 합쳐진 리듀서를 우리는 **루트 리듀서**라고 부릅니다.

리듀서를 합치는 작업은 리덕스에 내장되어있는 `combineReducers라는` 함수를 사용합니다.

### 1. 리듀서 합치기 (./src/modules)

```javascript
// modules.index.js

import { combineReducers } from "redux";
import counter from "./counter";
import todos from "./todos";

const rootReducer = combineReducers({
  counter,
  todos
});

export default rootReducer;
```

### 2. 리덕스 스토어 만들기 (./src)

```javascript
// src/index.js
import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import App from "./App";
import * as serviceWorker from "./serviceWorker";
import { createStore } from "redux";
// ./modules/index.js = ./modules
import rootReducer from "./modules";

// 스토어 생성
const store = createStore(rootReducer);
// 스토어 상태 확인
console.log(store.getState());

ReactDOM.render(<App />, document.getElementById("root"));

serviceWorker.unregister();
```

## 리액트 프로젝트에 리덕스 적용하기

react-redux 라이브러리 사용

```cmd
$ yarn add react-redux
```

```javascript
// src/index.js
// ...
import { Provider } from "react-redux";

// 스토어 생성
const store = createStore(rootReducer);
// 스토어 상태 확인
console.log(store.getState());

ReactDOM.render(
  <Provider store={store}>
    <App />
  </Provider>,
  document.getElementById("root")
);
```

Provider로 store를 넣어서 App 을 감싸게 되면 우리가 렌더링하는 그 어떤 컴포넌트던지 리덕스 스토어에 접근 할 수 있게 된답니다.

# 5. 카운터 구현하기

## 프레젠테이셔널 컴포넌트 만들기

**프리젠테이셔널 컴포넌트**란, 리덕스 스토어에 직접적으로 접근하지 않고 필요한 값 또는 함수를 props 로만 받아와서 사용하는 컴포넌트입니다. (UI 컴포넌트)

```javascript
// components/counter.js
import React from "react";

function counter({ number, diff, onIncrease, onDecrease, onSetDiff }) {
  const onChange = e => {
    onSetDiff(parseInt(e.target.value, 10));
  };
  return (
    <div>
      <h1>{number}</h1>
      <div>
        <input type="number" value={diff} min="1" onChange={onChange} />
        <button onClick={onIncrease}>+</button>
        <button onClick={onDecrease}>-</button>
      </div>
    </div>
  );
}

export default counter;
```

프리젠테이셔널 컴포넌트에선 <mark>주로 이렇게 UI를 선언하는 것에 집중</mark>하며, 필요한 값들이나 함수는 props 로 받아와서 사용하는 형태로 구현합니다.

## 컨테이너 컴포넌트 만들기

컨테이너 컴포넌트란, <u>리덕스 스토어의 상태를 조회하거나, 액션을 디스패치 할 수 있는 컴포넌트</u>를 의미합니다. 그리고, HTML 태그들을 사용하지 않고 다른 프리젠테이셔널 컴포넌트들을 불러와서 사용합니다.

```javascript
// containers/CounterContainer.js

import React from "react";
import { useSelector, useDispatch } from "react-redux";
import { increase, decrease, setDiff } from "../modules/counter";
import Counter from "../components/Counter";

function CounterContainer() {
  // useSelector는 리덕스 스토어의 상태를 조회
  // state의 값은 store.getState() 함수를 호출했을 때 나타나는 결과물과 동일
  const { number, diff } = useSelector(state => ({
    number: state.counter.number,
    diff: state.counter.diff
  }));

  console.log(number);

  // useDispatch는 리덕스 스토어의 dispatch를 함수에서 사용할 수 있게!
  const dispatch = useDispatch();
  // 액션 디스패치
  const onIncrease = () => dispatch(increase());
  const onDecrease = () => dispatch(decrease());
  const onSetDiff = diff => dispatch(setDiff(diff));

  return (
    <Counter
      number={number}
      diff={diff}
      onIncrease={onIncrease}
      onDecrease={onDecrease}
      onSetDiff={onSetDiff}
    />
  );
}

export default CounterContainer;
```

## 프레젠테이셔널 컴포넌트와 컨테이너 컴포넌트

우리가 이번에 리액트 컴포넌트에서 리덕스를 사용 할 때 프리젠테이셔널 컴포넌트와 컨테이너 컴포넌트를 분리해서 작업을 했습니다. [이러한 패턴](https://medium.com/@dan_abramov/smart-and-dumb-components-7ca2f9a7c7d0)을 리덕스의 창시자 Dan Abramov가 소개하게 되면서 이렇게 컴포넌트들을 구분지어서 진행하는게 당연시 됐었습니다.

하지만, 꼭 이렇게 하실 필요는 없습니다. Dan Abramov 또한 2019년에 자신이 썼던 포스트를 수정하게 되면서 꼭 이런 형태로 할 필요는 없다고 명시하였습니다.

순전히 여러분이 편하다고 생각하는 방식을 택하시면 됩니다.

저는 개인적으로 프리젠테이셔널 / 컨테이너 컴포넌트를 구분지어서 작성하긴 하지만 디렉터리 상으로는 따로 구분 짓지 않는 것을 선호합니다.

하지만 컴포넌트를 분리해서 작성하는 것이 아직까진 정석이긴 하기 때문에 이번 리덕스 강의에서는 분리해서 작성을 하겠습니다.

# 6. 리덕스 개발자도구 적용하기

[크롬 웹스토어](https://chrome.google.com/webstore/detail/redux-devtools/lmhkpmbekcpmknklioeibfkpmmfibljd)

[redux-devtools-extension](https://www.npmjs.com/package/redux-devtools-extension)

```cmd
$ yarn add redux-devtools-extension
```

```javascript
import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import App from "./App";
import * as serviceWorker from "./serviceWorker";
import { createStore } from "redux";
// ./modules/index.js = ./modules
import rootReducer from "./modules";
import { Provider } from "react-redux";
import { composeWithDevTools } from "redux-devtools-extension";

// 스토어 생성
// ★리덕스 개발자 도구 활성화
const store = createStore(rootReducer, composeWithDevTools());
// 스토어 상태 확인
console.log(store.getState());

ReactDOM.render(
  <Provider store={store}>
    <App />
  </Provider>,
  document.getElementById("root")
);

serviceWorker.unregister();
```
