[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 2. 파라미터와 쿼리

페이지 주소를 정의 할 때, 우리는 유동적인 값을 전달해야 할 때도 있습니다. 이는 파라미터와 쿼리로 나뉘어질 수 있는데요:

```
파라미터: /profiles/velopert
쿼리: /about?details=true
```

이것을 사용하는것에 대하여 무조건 따라야 하는 규칙은 없지만, 일반적으로는 <u>파라미터는 특정 id 나 이름을 가지고 조회를 할 때 사용</u>하고, <u>쿼리의 경우엔 어떤 키워드를 검색하거나, 요청을 할 때 필요한 옵션을 전달 할 때 사용</u>됩니다.

## URL Params

```javascript
// Profile.js
import React from "react";

const profileData = {
  velopert: {
    name: "김민준",
    description: "Front End Engineer"
  },
  gildong: {
    name: "길동이",
    description: "나는야 길동이"
  }
};

function Profile({ match }) {
  // 파라미터를 받아올 때 match 안 params 값을 참조!
  const { username } = match.params;
  const profile = profileData[username];
  if (!profile) {
    return <div>존재하지 않는 사용자</div>;
  }
  return (
    <div>
      <h3>
        {username}({profile.name})
      </h3>
      <p>{profile.description}</p>
    </div>
  );
}

export default Profile;
```

파라미터를 받아올 땐 `match` 안에 들어있는 `params` 값을 참조합니다. [match](https://reacttraining.com/react-router/web/api/match) 객체안에는 현재의 주소가 `Route` 컴포넌트에서 정한 규칙과 어떻게 일치하는지에 대한 정보가 들어있습니다.

이제 Profile 을 App 에서 적용해볼건데요, path 규칙에는 `/profiles/:username` 이라고 넣어주면 username 에 해당하는 값을 파라미터로 넣어주어서 Profile 컴포넌트에서 match props 를 통하여 전달받을 수 있게 됩니다.

```javascript
// App.js
<Route path="/profile/:username" component={Profile} />
```

## Query

이번엔 About 페이지에서 쿼리를 받아오겠습니다. 쿼리는 라우트 컴포넌트에게 `props` 전달되는 `location` 객체에 있는 `search` 값에서 읽어올 수 있습니다. `location` 객체는 현재 앱이 갖고있는 주소에 대한 정보를 지니고있습니다.

이런식으로 말이죠:

```javascript
{
  key: 'ac3df4', // not with HashHistory!
  pathname: '/somewhere'
  search: '?some=search-string',
  hash: '#howdy',
  state: {
    [userDefined]: true
  }
}
```

여기서 search 값을 확인해야하는데, 이 값은 문자열 형태로 되어있습니다. 객체 형태로 변환하는건 우리가 직접 해주어야 합니다.

이 작업은 [qs](https://www.npmjs.com/package/qs) 라는 라이브러리를 사용하여 쉽게 할 수 있습니다.

라이브러리 설치

```cmd
$ yarn add qs
```

```javascript
// About.js
import React from "react";
import qs from "qs";

function About({ location }) {
  const query = qs.parse(location.search, {
    ignoreQueryPrefix: true
  });
  const detail = query.detail === "true";
  return (
    <div>
      <h1> 소개 페이지 </h1>
      {detail && <p>추가 정보가 있나보다!</p>}
    </div>
  );
}

export default About;
```

> params는 match, query는 location.search !
