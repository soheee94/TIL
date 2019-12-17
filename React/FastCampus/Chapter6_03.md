[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 3. 리덕스 사용 할 준비하기

리덕스 설치

```cmd
$ yarn add redux
```

```javascript
// exercise.js
import { createStore } from "redux";

// createStore는 스토어를 만들어주는 함수
// 오직 단 하나의 스토어!!

// 리덕스에서 관리할 상태 정의
const initialState = {
  counter: 0,
  text: "",
  list: []
};

// 액션 타입 정의
// 액션 타입은 주로 대문자로 작성
const INCREASE = "INCREASE";
const DECREASE = "DECREASE";
const CHANGE_TEXT = "CHANGE_TEXT";
const ADD_TO_LIST = "ADD_TO_LIST";

// 액션 생성 함수 정의
// 액션 생성 함수는 주로 camelCase 로 작성

function increase() {
  // 액션 객체에는 type 값 필수
  return {
    type: INCREASE
  };
}

// 화살표 함수로 사용하면 코드가 간단해지기 때문에 추천!
const decrease = () => ({
  type: DECREASE
});

const changeText = text => ({
  type: CHANGE_TEXT,
  text
});

const addToList = item => ({
  type: ADD_TO_LIST,
  item
});

// 리듀서 만들기
// 위 액션 생성함수들을 통해 만들어진 객체들을 참조하여 새로운 상태를 만드는 함수
// 리듀서는 꼭 불변성을 지켜주어야한다!!!!!

function reducer(state = initialState, action) {
  // state 초기값을 지정해주어야 undefined 에러 방지
  switch (action.type) {
    case INCREASE:
      return {
        ...state,
        counter: state.counter + 1
      };
    case DECREASE:
      return {
        ...state,
        counter: state.counter - 1
      };
    case CHANGE_TEXT:
      return {
        ...state,
        text: action.text
      };
    case ADD_TO_LIST:
      return {
        ...state,
        list: state.list.concat(action.item)
      };
    default:
      return state;
  }
}

// 스토어 만들기
const store = createStore(reducer);
// console.log(store.getState())
// 현재 store 안에 들어있는 상태 조회

// 스토어 안에 들어있는 상태가 바뀔 때마다 호출되는 listener 함수
const listener = () => {
  const state = store.getState();
  console.log(state);
};

// 액션이 디스패치 되었을 때 마다 전달해준 함수가 호출
const unsubscribe = store.subscribe(listener);
// unsubscribe();
// 구독 해제 시에는 unsubscribe() 호출

// 액션들을 디스패치 해봅시다.
store.dispatch(increase());
store.dispatch(decrease());
store.dispatch(changeText("안녕하세요"));
store.dispatch(addToList({ id: 1, text: "와우" }));

// 크롬 개발자 도구에서 사용
window.store = store;
```
