[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 7. thunk에서 라우터 연동하기

일단, 컨테이너 컴포넌트내에서 그냥 단순히 withRouter를 사용해서 props 로 `history` 를 가져와서 사용해도 상관은 없습니다. 하지만 thunk에서 처리를 하면 코드가 훨씬 깔끔해질 수 있습니다. 취향에 따라 택하시면 됩니다.

## customHistory 만들기

thunk 에서 라우터의 history 객체를 사용하려면, BrowserHistory 인스턴스를 직접 만들어서 적용해야합니다. index.js 를 다음과 같이 수정해주세요.
그리고, redux-thunk 의 `withExtraArgument` 를 사용하면 thunk함수에서 사전에 정해준 값들을 참조 할 수 있습니다.

```javascript
// index.js
import { Router } from "react-router-dom";
import { createBrowserHistory } from "history";

const customHistory = createBrowserHistory();

const store = createStore(
  rootReducer,
  // logger 를 사용하는 경우, logger가 가장 마지막에 와야합니다.
  composeWithDevTools(
    applyMiddleware(ReduxThunk.withExtraArgument({ history: customHistory }), logger)
  )
); // 여러개의 미들웨어를 적용 할 수 있습니다.

ReactDOM.render(
  <Router history={customHistory}>
    <Provider store={store}>
      <App />
    </Provider>
  </Router>,
  document.getElementById("root")
);

serviceWorker.unregister();
```

## 홈 화면으로 가는 thunk 만들기

```javascript
// modules/posts.js - goToHome
// 3번째 인자를 사용하면 withExtraArgument 에서 넣어준 값들을 사용 할 수 있습니다.
export const goToHome = () => (dispatch, getState, { history }) => {
  history.push("/");
};
```

```javascript
// containers/PostContainer.js
return (
  <>
    <button onClick={() => dispatch(goToHome())}>홈으로 이동</button>
    <Post post={data} />
  </>
);
```

실제 프로젝트에서는 `getState()` 를 사용하여 현재 리덕스 스토어의 상태를 확인하여 조건부로 이동을 하거나, 특정 API를 호출하여 성공했을 시에만 이동을 하는 형식으로 구현을 할 수 있답니다.
