[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 3. redux-logger 사용 및 미들웨어와 DevTools 함께 사용하기

## redux-logger 설치

```cmd
yarn add redux-logger
```

```javascript
// index.js
import logger from "redux-logger";

const store = createStore(rootReducer, applyMiddleware(myLogger, logger));
// 여러개의 미들웨어 사용 가능
// myLogger 에서  next(action); // 다음 미들웨어 부분에 logger가 실행!
```

## Redux DevTools 사용하기

```javascript
import { composeWithDevTools } from "redux-devtools-extension";

const store = createStore(
  rootReducer,
  composeWithDevTools(applyMiddleware(logger))
);
```

# 4. redux-thunk

## 소개

redux-thunk는 리덕스에서 **비동기 작업을 처리 할 때 가장 많이 사용하는 미들웨어**입니다. 이 미들웨어를 사용하면 액션 객체가 아닌 함수를 디스패치 할 수 있습니다. redux-thunk는 리덕스의 창시자인 Dan Abramov가 만들었으며, 리덕스 공식 매뉴얼에서도 비동기 작업을 처리하기 위하여 미들웨어를 사용하는 예시를 보여줍니다.

```javascript
const thunk = store => next => action =>
  typeof action === "function"
    ? action(store.dispatch, store.getState)
    : next(action);
```

이 미들웨어를 사용하면 <u>함수를 디스패치 할 수 있다</u>고 했는데요, 함수를 디스패치 할 때에는, 해당 함수에서 `dispatch` 와 `getState` 를 파라미터로 받아와주어야 합니다. **이 함수를 만들어주는 함수**를 우리는 **thunk** 라고 부릅니다.

```javascript
//thunk 사용 예시

const getComments = () => (dispatch, getState) => {
  // 이 안에서는 액션을 dispatch 할 수도 있고
  // getState를 사용하여 현재 상태도 조회 할 수 있습니다.
  const id = getState().post.activeId;

  // 요청이 시작했음을 알리는 액션
  dispatch({ type: "GET_COMMENTS" });

  // 댓글을 조회하는 프로미스를 반환하는 getComments 가 있다고 가정해봅시다.
  api
    .getComments(id) // 요청을 하고
    .then(comments => dispatch({ type: "GET_COMMENTS_SUCCESS", id, comments })) // 성공시
    .catch(e => dispatch({ type: "GET_COMMENTS_ERROR", error: e })); // 실패시
};
```

```javascript
//async, await 사용 예시
const getComments = () => async (dispatch, getState) => {
  const id = getState().post.activeId;
  dispatch({ type: "GET_COMMENTS" });
  try {
    const comments = await api.getComments(id);
    dispatch({ type: "GET_COMMENTS_SUCCESS", id, comments });
  } catch (e) {
    dispatch({ type: "GET_COMMENTS_ERROR", error: e });
  }
};
```

## redux-thunk 설치 및 적용

```javascript
import logger from "redux-logger";
import { composeWithDevTools } from "redux-devtools-extension";
import ReduxThunk from "redux-thunk";

// logger를 사용하는 경우 제일 마지막에 두기!!!
const store = createStore(
  rootReducer,
  composeWithDevTools(applyMiddleware(ReduxThunk, logger))
);
```

## 카운터 딜레이 하기

```javascript
//modules/counter.js

// () => 부터는 thunk 를 생성해주는 함수
// dispatch => 부터가 thunk 함수
export const increaseAsync = () => dispatch => {
  setTimeout(() => dispatch(increase()), 1000);
};
export const decreaseAsync = () => dispatch => {
  setTimeout(() => dispatch(decrease()), 1000);
};
```
