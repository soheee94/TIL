[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 3. 서브라우트

서브 라우트는, <u>라우트 내부의 라우트를 만드는것을 의미</u>합니다. 이 작업은 그렇게 복잡하지 않습니다. 그냥 컴포넌트를 만들어서 그 안에 또 Route 컴포넌트를 렌더링하시면 됩니다.

## 서브 라우트 만들기

```javascript
// Profiles.js
import React from "react";
import { Link, Route } from "react-router-dom";
import Profile from "./Profile";

function Profiles() {
  return (
    <div>
      <h3>유저 목록 : </h3>
      <ul>
        <li>
          <Link to="/profiles/velopert">velopert</Link>
        </li>
        <li>
          <Link to="/profiles/gildong">gildong</Link>
        </li>
      </ul>

      <Route
        path="/profiles"
        exact
        render={() => <div>유저를 선택해주세요</div>}
      />
      <Route path="/profiles/:username" component={Profile} />
    </div>
  );
}

export default Profiles;
```

위 코드에서 첫번째 Route 컴포넌트에서는 `component` 대신에 `render` 가 사용되었는데요, <u>여기서는 컴포넌트가 아니라, JSX 자체를 렌더링 할 수 있습니다.</u> JSX 를 렌더링하는 것이기에, 상위 영역에서 `props` 나 기타 값들을 필요하면 전달 해 줄 수있습니다.

만약에 여러분이 만들게되는 서비스에서 특정 라우트 내에 탭 같은것을 만들게 된다면, <mark>단순히 state 로 관리하는 것 보다 서브 라우트로 관리를 하는 것을 권장드립니다.</mark> 그 이유는, setState 같은것을 할 필요도 없고, 링크를 통하여 다른 곳에서 쉽게 진입 시킬 수도 있고, 나중에 검색엔진 크롤링 까지 고려한다면, <u>검색엔진 봇이 더욱 다양한 데이터를 수집해 갈 수 있기 때문입니다.</u>
