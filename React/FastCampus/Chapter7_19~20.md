[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 11. redux-saga로 프로미스 다루기

redux-thunk에서는 함수를 만들어서 해당 함수에서 비동기 작업을 하고 필요한 시점에 특정 액션을 디스패치합니다. redux-saga는 비동기 작업을 처리 할 때 다른 방식으로 처리합니다.

redux-saga에서는 특정 액션을 모니터링하도록 하고, 해당 액션이 주어지면 이에 따라 제너레이터 함수를 실행하여 비동기 작업을 처리 후 액션을 디스패치합니다.

기존에 redux-thunk로 구현했던 posts 모듈을 redux-saga로 구현해봅시다.

```javascript
// modules/posts.js
// SAGA

export const getPosts = () => ({ type: GET_POSTS });
// payload : 파라미터 용도, meta: 리듀서에서 알기 위한 용도
// export const getPost = id => ({ type: GET_POST, payload: id, meta: id });
export const getPost = id => ({ type: GET_POST, meta: id });

function* getPostsSaga() {
  try {
    const posts = yield call(postsAPI.getPosts);
    yield put({
      type: GET_POSTS_SUCCESS,
      payload: posts
    });
  } catch (e) {
    yield put({
      type: GET_POSTS_ERROR,
      payload: e,
      error: true
    });
  }
}

function* getPostSaga(action) {
  //   const param = action.payload;
  const id = action.meta;
  try {
    const post = yield call(postsAPI.getPostById, id);
    yield put({
      type: GET_POST_SUCCESS,
      payload: post,
      meta: id
    });
  } catch (e) {
    yield put({
      type: GET_POST_ERROR,
      payload: e,
      error: true
    });
  }
}

// 사가들을 합치기
export function* postsSaga() {
  yield takeEvery(GET_POSTS, getPostsSaga);
  yield takeEvery(GET_POST, getPostSaga);
}
```

기존에 redux-thunk로 구현 할 때에는 `getPosts` 와 `getPost` 는 thunk 함수였는데, 이제는 redux-saga를 사용하니까 **순수 액션 객체를 반환하는 액션 생성 함수**로 구현 할 수 있습니다.

액션을 모니터링해서 특정 액션이 발생했을 때 호출할 사가 함수에서는 파라미터로 해당 액션을 받아올 수 있습니다. 그래서 getPostSaga의 경우엔 액션을 파라미터로 받아와서 해당 액션의 id 값을 참조 할 수 있죠.

예를 들어서, dispatch({ type: GET_POST, payload: 1, meta: 1 })이란 코드가 실행 되면 액션에서 action.payload값을 추출하여 API를 호출 할 때 인자로 넣어서 호출하는 것 입니다. 여기서 meta 값이 있는 이유는 우리가 이전에 만들었던 handleAsyncActionsById 를 호환시키기 위함입니다. 만약 handleAsyncActionsById를 사용하지 않는다면 meta 를 생략하셔도 됩니다. 그리고 추후 우리가 리팩토링 과정에서 프로미스를 처리하는 사가 함수를 쉽게 만드는 함수를 만들건데요, 만약에 리팩토링을 하지 않을거라면 사실상 { type: GET_POST, id } 이런식으로 파라미터를 꼭 payload 라고 설정 할 필요는 없습니다.

코드를 다 작성하셨다면 rootSaga에 우리가 방금 만든 postsSaga를 등록해주세요.

```javascript
// modules/index.js

export function* rootSaga() {
  yield all([counterSaga(), postsSaga()]); // all 은 배열 안의 여러 사가를 동시에 실행시켜줍니다.
}
```

redux-saga를 사용하면 이렇게 **순수 액션 객체만을 디스패치**해서 비동기 작업을 처리 할 수 있게 됩니다.

## 프로미스를 처리하는 사가 리팩토링

```javascript
// lib/asyncUtils
// 프로미스를 기다렸다가 결과를 디스패치하는 사가
export const createPromiseSaga = (type, promiseCreator) => {
  const [SUCCESS, ERROR] = [`${type}_SUCCESS`, `${type}_ERROR`];
  return function*(action) {
    try {
      const payload = yield call(promiseCreator, action.payload);
      yield put({
        type: SUCCESS,
        payload
      });
    } catch (e) {
      yield put({
        type: ERROR,
        payload: e,
        error: true
      });
    }
  };
};

// 프로미스를 기다렸다가 결과를 디스패치하는 사가
export const createPromiseSagaById = (type, promiseCreator) => {
  const [SUCCESS, ERROR] = [`${type}_SUCCESS`, `${type}_ERROR`];
  return function*(action) {
    const id = action.meta;
    try {
      const payload = yield call(promiseCreator, action.payload);
      yield put({
        type: SUCCESS,
        payload,
        meta: id
      });
    } catch (e) {
      yield put({
        type: ERROR,
        payload: e,
        error: true,
        meta: id
      });
    }
  };
};
```

```javascript
// modules/posts.js
const getPostsSaga = createPromiseSaga(GET_POSTS, postsAPI.getPosts);
const getPostSaga = createPromiseSaga(GET_POST, postsAPI.getPostById);
```
