[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 1. 리덕스 프로젝트 준비하기

# 2. 리덕스 미들웨어 만들어보고 이해하기

## 리덕스 미들웨어의 템플릿

```javascript
const middleware = store => next => action => {
  // 하고 싶은 작업...
};
```

미들웨어는 결국 하나의 함수입니다. 함수를 연달아서 두번 리턴하는 함수죠. 화살표가 여러번 나타나는게 도대체 뭐지, 하고 헷갈릴 수도 있을텐데요, 이 함수를 function 키워드를 사용하여 작성한다면 다음과 같습니다.

```javascript
function middleware(store) {
  return function(next) {
    return function(action) {
      // 하고 싶은 작업...
    };
  };
}
```

- `store` : 리덕스 스토어 인스턴스 (`dispatch`, `getState`, `subscribe` 내장함수들 포함)
- `next` : 액션을 다음 미들웨어에게 전달하는 함수, `next(action)` 이런 형태로 사용합니다. 만약 다음 미들웨어가 없다면 리듀서에게 액션을 전달해줍니다. 만약에 `next` 를 호출하지 않게 된다면 액션이 무시처리되어 리듀서에게로 전달되지 않습니다.
- `action` : 현재 처리하고 있는 액션 객체

![미들웨어의 구조](https://i.imgur.com/fZs5yvY.png)

미들웨어는 위와 같은 구조로 작동합니다. 리덕스 스토어에는 여러 개의 미들웨어를 등록할 수 있습니다. 새로운 액션이 디스패치 되면 첫 번째로 등록한 미들웨어가 호출됩니다. 만약에 미들웨어에서 `next(action)`을 호출하게 되면 다음 미들웨어로 액션이 넘어갑니다. 그리고 만약 미들웨어에서 `store.dispatch` 를 사용하면 다른 액션을 추가적으로 발생시킬 수 도 있습니다.

## 미들웨어 직접 작성하기

```javascript
//middlewares/myLogger.js
const myLogger = store => next => action => {
  console.log(action); // 먼저 액션을 출력합니다.
  const result = next(action); // 다음 미들웨어 (또는 리듀서) 에게 액션을 전달합니다.
  return result; // 여기서 반환하는 값은 dispatch(action)의 결과물이 됩니다. 기본: undefined
};

export default myLogger;
```

## 미들웨어 적용하기

```javascript
// index.js
import { createStore, applyMiddleware } from "redux";

const store = createStore(rootReducer, applyMiddleware(myLogger));
```

## 미들웨어 수정하기

```javascript
//middlewares/myLogger.js
const myLogger = store => next => action => {
  console.log(action); // 먼저 액션을 출력합니다.

  // 업데이트 이전의 상태를 조회합니다.
  console.log("\t", store.getState()); // '\t' 는 탭 문자 입니다.

  const result = next(action); // 다음 미들웨어 (또는 리듀서) 에게 액션을 전달합니다.

  // 업데이트 이후의 상태를 조회합니다.
  console.log("\t", store.getState()); // '\t' 는 탭 문자 입니다.

  return result; // 여기서 반환하는 값은 dispatch(action)의 결과물이 됩니다. 기본: undefined
};

export default myLogger;
```
