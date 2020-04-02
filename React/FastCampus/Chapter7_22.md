[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 13. select로 현재 상태 조회하기

redux-saga 에서 state의 현재 상태를 조회하기 위헤서 `select` 라는 유틸함수를 사용한다.

```javascript
// modules/posts.js

import { takeEvery, getContext, select } from "redux-saga/effects";

const PRINT_STATE = "PRINT_STATE";
export const printState = () => ({ type: PRINT_STATE });

function* printStateSaga() {
  const state = yield select(state => state.posts);
  console.log(state);
}

export function* postsSaga() {
  yield takeEvery(GET_POSTS, getPostsSaga);
  yield takeEvery(GET_POST, getPostSaga);
  yield takeEvery(GO_TO_HOME, goToHomeSaga);
  yield takeEvery(PRINT_STATE, printStateSaga);
}
```

상태 출력 확인

```javascript
// containers/PostContainer.js
return (
  <>
    <button onClick={() => dispatch(goToHome())}>홈으로 이동</button>
    <button onClick={() => dispatch(printState())}>상태 출력</button>
    <Post post={data} />
  </>
);
```
