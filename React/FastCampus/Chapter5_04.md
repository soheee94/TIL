[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 4. 리액트 라우터 부가기능

## history 객체

[history](https://reacttraining.com/react-router/web/api/history) 객체는 라우트로 사용된 컴포넌트에게 match(params), location(query) 과 함께 전달되는 `props` 중 하나입니다. 이 객체를 통하여, 우리가 컴포넌트 내에 구현하는 메소드에서 <mark>라우터에 직접 접근</mark>을 할 수 있습니다 - 뒤로가기, 특정 경로로 이동, 이탈 방지 등..

```javascript
// HistorySample.js
import React, { useEffect } from "react";

function HistorySample({ history }) {
  const goBack = () => {
    history.goBack();
  };
  const goHome = () => {
    history.push("/");
  };

  useEffect(() => {
    console.log(history);
    // length : 기록의 길이
    // action : push(진입!), pop(다른곳으로!)
    // goBack : 뒤로
    // goForward : 앞으로
    const unblock = history.block("떠날고야?");
    return () => {
      // unmount시 이탈 막기
      unblock();
    };
  }, [history]);
  return (
    <div>
      <button onClick={goBack}>뒤로가기</button>
      <button onClick={goHome}>홈으로</button>
    </div>
  );
}

export default HistorySample;
```

이렇게 `history` 객체를 사용하면 조건부로 다른 곳으로 이동도 가능하고, 이탈을 메시지박스를 통하여 막을 수 도 있습니다.

## withRouter HoC

withRouter HoC 는 라우트 컴포넌트가 아닌곳에서 match / location / history 를 사용해야 할 때 쓰면 됩니다.

```javascript
// WithRouterSample.js
import React from "react";
import { withRouter } from "react-router-dom";

function WithRouterSample({ location, match, history }) {
  return (
    <div>
      <h4>location</h4>
      <textarea value={JSON.stringify(location, null, 2)} readOnly />
      <h4>match</h4>
      <textarea value={JSON.stringify(match, null, 2)} readOnly />
      <button onClick={() => history.push("/")}>home</button>
    </div>
  );
}

// withRouter로 감싸주기!
export default withRouter(WithRouterSample);
```

profiles.js에서 렌더링

```javascript
// profiles/velopert
location
{
  "pathname": "/profiles/velopert",
  "search": "",
  "hash": "",
  "key": "k4i7d6"
}

match
{
  "path": "/profiles",
  "url": "/profiles",
  "isExact": false,
  "params": {}
}
```

withRouter 를 사용하면, <u>자신의 부모 컴포넌트 기준의 match 값이 전달됩니다.</u> 보시면, 현재 velopert 이라는 URL Params 가 있는 상황임에도 불구하고 params 쪽이 {} 이렇게 비어있죠? WithRouterSample 은 Profiles 에서 렌더링 되었고, 해당 컴포넌트는 /profile 규칙에 일치하기 때문에 이러한 결과가 나타났습니다.
