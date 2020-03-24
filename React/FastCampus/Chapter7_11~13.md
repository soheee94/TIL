[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 6. API 재로딩 문제 해결하기

## 포스트 목록 재로딩 문제 해결하기

### 1. 데이터가 이미 존재 한다면 요청하지 않기

```javascript
// PostListContainer.js
useEffect(() => {
  if (data) return;
  dispatch(getPosts());
}, [dispatch, data]);
```

### 2. 최신 데이터를 불러오지만 로딩중은 띄우지 않기

```javascript
// asyncUtils.js - handleAsyncActions
export const handleAsyncActions = (type, key, keepData = false) => {
  const [SUCCESS, ERROR] = [`${type}_SUCCESS`, `${type}_ERROR`];
  return (state, action) => {
    switch (action.type) {
      case type:
        return {
          ...state,
          [key]: reducerUtils.loading(keepData ? state[key].data : null)
        };
      // 생략
    }
  };
};
```

`keepData` 라는 파라미터를 추가하여 만약 이 값이 `true`로 주어지면 로딩을 할 때에도 데이터를 유지하도록 수정을 해주었습니다.

```javascript
// modules/posts.js - posts 리듀서
export default function posts(state = initialState, action) {
  switch (action.type) {
    case GET_POSTS:
    case GET_POSTS_SUCCESS:
    case GET_POSTS_ERROR:
      return handleAsyncActions(GET_POSTS, "posts", true)(state, action);
    // 생략
  }
}
```

```javascript
//container/PostListContainer.js
// 컴포넌트 마운트 후 포스트 목록 요청
useEffect(() => {
  dispatch(getPosts());
}, [dispatch]);

if (loading && !data) return <div>로딩중...</div>;
```

이렇게 구현을 해주면, 뒤로가기 눌렀을 때 새 데이터를 요청하지만, '로딩중' 문구를 안 볼 수 있다!

## 포스트 조회시 재로딩 문제 해결

### 1. 컴포넌트가 언마운트될 때 포스트 내용 비우기

```javascript
// modules/post.js
// 포스트 비우기
const CLEAR_POST = "CLEAR_POST";

export const clearPost = () => ({ type: CLEAR_POST });

export default function posts(state = initialState, action) {
  switch (action.type) {
    // 생략
    case CLEAR_POST:
      return {
        ...state,
        post: reducerUtils.initial()
      };
    default:
      return state;
  }
}
```

```javascript
// containers/PostContainer.js
useEffect(() => {
  dispatch(getPost(postId));
  return () => {
    dispatch(clearPost());
  };
}, [postId, dispatch]);
```

그러나 이미 읽었던 포스트를 불러오려고 할 경우에도 새로 요청을 하게 된다.

### 2. 모듈 관리 상태의 구조 변경

```javascript
// 기존
{
  posts: {
    data,
    loading,
    error
  },
  post: {
    data,
    loading,
    error,
  }
}
```

```javascript
// 변경
{
  posts: {
    data,
    loading,
    error
  },
   post: {
    '1': {
      data,
      loading,
      error
    },
    '2': {
      data,
      loading,
      error
    },
    [id]: {
      data,
      loading,
      error
    }
  }
}
```

데이터를 제대로 캐싱하고 싶다면 아예 요청을 하지 않는 방식을 택하시고, 포스트 정보가 바뀔 수 있는 가능성이 있다면 새로 불러오긴 하지만 로딩중은 표시하지 않는 형태로 구현을 하시면 되겠습니다.
