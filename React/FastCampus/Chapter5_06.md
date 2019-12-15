[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 5. useReactRouter Hook 사용하기

지난 섹션에서 `withRouter` 라는 함수를 사용해서 라우트로 사용되고 있지 않은 컴포넌트에서도 라우트 관련 props 인 `match`, `history`, `location` 을 조회하는 방법을 확인해보았습니다.

withRouter 를 사용하는 대신에 Hook 을 사용해서 구현을 할 수도 있는데요, 아직은 리액트 라우터에서 공식적인 Hook 지원은 되고 있지 않습니다 (될 예정이긴 합니다).

그 전까지는, 다른 라이브러리를 설치해서 Hook 을 사용하여 구현을 할 수 있습니다. 이번 튜토리얼에서는 라이브러리를 설치해서 라우터에 관련된 값들을 Hook 으로 사용하는 방법을 알아보도록 하겠습니다.

```cmd
$ yarn add use-react-router
```

```javascript
// RouterHookSample.js
import useReactRouter from "use-react-router";

function RouterHookSample() {
  const { history, location, match } = useReactRouter;
  console.log({ history, location, match });
  return null;
}

export default RouterHookSample;
```

이 Hook 이 정식 릴리즈는 아니기 때문에 만약에 여러분들이 `withRouter` 가 너무 불편하다고 느낄 경우에만 사용하시는 것을 권장드립니다.
