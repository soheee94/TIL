[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 12. saga에서 라우터 연동하기

우리가 이전에 redux-thunk를 배울 때 thunk함수에서 리액트 라우터의 history 를 사용하는 방법을 배워보았습니다.

예를 들어서 로그인 요청을 할 때 성공 할 시 특정 주소로 이동시키고, 실패 할 시엔 그대로 유지하는 기능을 구현 해야 된다면, 컨테이너 컴포넌트에서 `withRouter`를 사용해서 구현을 하는 것 보다 사가 내부에서 처리를 하는것이 훨씬 편합니다.

구현 방식은 redux-thunk에서 했던 방식과 꽤나 비슷합니다. 미들웨어를 만들 때 `context`를 설정해주면 추후 사가에서 `getContext` 함수를 통해 조회 할 수 있습니다.

```javascript
// index.js

const customHistory = createBrowserHistory();
const sagaMiddleware = createSagaMiddleware({
  context: {
    history: customHistory
  }
});
```

```javascript
// modules/post.js

const GO_TO_HOME = "GO_TO_HOME";

export const goToHome = () => ({ type: GO_TO_HOME });

function* goToHomeSaga() {
  const history = yield getContext("history");
  history.push("/");
}

export function* postsSaga() {
  yield takeEvery(GET_POSTS, getPostsSaga);
  yield takeEvery(GET_POST, getPostSaga);
  yield takeEvery(GO_TO_HOME, goToHomeSaga);
}
```
