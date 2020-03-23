[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 5. redux-thunk로 프로미스 다루기

## 가짜 API 함수 만들기

```javascript
//api/posts.js
// n 밀리세컨동안 기다리는 프로미스 함수
const sleep = n => new Promise(resolve => setTimeout(resolve, n));

const posts = [
  {
    id: 1,
    title: "테스트",
    body: "이거는 테스트 1번이에요"
  },
  {
    id: 2,
    title: "테스트2",
    body: "이거는 테스트 2번이에요"
  },
  {
    id: 3,
    title: "테스트",
    body: "이거는 테스트 3번이에요"
  }
];

export const getPosts = async () => {
  await sleep(500);
  return posts;
};

export const getPostById = async id => {
  await sleep(500);
  return posts.find(post => post.id === id);
};
```

## 리덕스 모듈 준비하기

프로미스를 다루는 리덕스 모듈을 다룰 땐 다음과 같은 사항을 고려해야합니다.

1. 프로미스가 시작, 성공, 실패했을때 다른 액션을 디스패치해야합니다.
2. 각 프로미스마다 thunk 함수를 만들어주어야 합니다.
3. 리듀서에서 액션에 따라 로딩중, 결과, 에러 상태를 변경해주어야 합니다.

리팩토링 완료 된 코드

```javascript
//modules/posts.js
import * as postsAPI from "../api/posts"; // api/posts 안의 함수 모두 불러오기
import {
  reducerUtils,
  createPromiseThunk,
  handleAsyncActions
} from "../lib/asyncUtils";

/* 액션 타입 */

// 포스트 여러개 조회하기
const GET_POSTS = "GET_POSTS"; // 요청 시작
const GET_POSTS_SUCCESS = "GET_POSTS_SUCCESS"; // 요청 성공
const GET_POSTS_ERROR = "GET_POSTS_ERROR"; // 요청 실패

// 포스트 하나 조회하기
const GET_POST = "GET_POST";
const GET_POST_SUCCESS = "GET_POST_SUCCESS";
const GET_POST_ERROR = "GET_POST_ERROR";

// thunk 를 사용 할 때, 꼭 모든 액션들에 대하여 액션 생성함수를 만들 필요는 없습니다.
// 그냥 thunk 함수에서 바로 액션 객체를 만들어주어도 괜찮습니다.

export const getPosts = createPromiseThunk(GET_POSTS, postsAPI.getPosts);
// thunk 함수에서도 파라미터를 받아와서 사용 할 수 있습니다.
export const getPost = createPromiseThunk(GET_POST, postsAPI.getPostById);

const initialState = {
  posts: reducerUtils.initial(),
  post: reducerUtils.initial()
};

export default function posts(state = initialState, action) {
  switch (action.type) {
    case GET_POSTS:
    case GET_POSTS_SUCCESS:
    case GET_POSTS_ERROR:
      return handleAsyncActions(GET_POSTS, "posts")(state, action);
    case GET_POST:
    case GET_POST_SUCCESS:
    case GET_POST_ERROR:
      return handleAsyncActions(GET_POST, "post")(state, action);
    default:
      return state;
  }
}
```

```javascript
//lib/asyncUtils.js
// Promise에 기반한 Thunk를 만들어주는 함수입니다.
export const createPromiseThunk = (type, promiseCreator) => {
  const [SUCCESS, ERROR] = [`${type}_SUCCESS`, `${type}_ERROR`];
  // 이 함수는 promiseCreator가 단 하나의 파라미터만 받는다는 전제하에 작성되었습니다.
  // 만약 여러 종류의 파라미터를 전달해야하는 상황에서는 객체 타입의 파라미터를 받아오도록 하면 됩니다.
  // 예: writeComment({ postId: 1, text: '댓글 내용' });
  // (dispatch, getState)
  return param => async dispatch => {
    // 요청 시작
    dispatch({ type, param });
    try {
      // 결과물의 이름은 payload로 통일
      const payload = await promiseCreator(param);
      // 성공
      dispatch({ type: SUCCESS, payload });
    } catch (e) {
      // 실패
      dispatch({ type: ERROR, payload: e, error: true });
    }
  };
};

// 리듀서에서 사용할 수 있는 유틸 함수
export const reducerUtils = {
  // 초기상태
  initial: (data = null) => ({
    data,
    loading: false,
    error: null
  }),
  // 로딩중 상태
  loading: (prevState = null) => ({
    loading: true,
    data: prevState,
    error: null
  }),
  // 성공 상태
  success: payload => ({
    loading: false,
    data: payload,
    error: null
  }),
  // 실패 상태
  error: error => ({
    error,
    loading: false,
    data: null
  })
};

export const handleAsyncActions = (type, key) => {
  const [SUCCESS, ERROR] = [`${type}_SUCCESS`, `${type}_ERROR`];
  return (state, action) => {
    switch (action.type) {
      case type:
        return {
          ...state,
          [key]: reducerUtils.loading()
        };
      case SUCCESS:
        return {
          ...state,
          [key]: reducerUtils.success(action.payload)
        };
      case ERROR:
        return {
          ...state,
          [key]: reducerUtils.error(action.payload)
        };
      default:
        return state;
    }
  };
};
```
