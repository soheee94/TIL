[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 8. CORS 와 Webpack DevServer Proxy

브라우저에서 기본적으로 API를 요청 할 때에는 브라우저의 현재 주소와 API 의 주소의 **도메인이 일치**해야만 데이터를 접근 할 수 있게 되어 있습니다. 만약 다른 도메인에서 API를 요청해서 사용 할 수 있게 해주려면 <u>CORS 설정이 필요</u>합니다.

json-server 로 만든 서버의 경우엔 모든 도메인을 허용해주는 CORS 규칙이 적용되어있습니다. 하지만, 여러분이 Open API를 만드는게 아니라면 모든 도메인을 허용하면 안됩니다. 한다면 **특정 도메인만 허용**을 해주어야 하죠.

나중에 여러분이 실제 서비스를 개발하게 될 때에는 서버의 API 를 요청해야 할 때, 기본적으로는 localhost:3000 에서 들어오는 것이 차단되기 때문에 서버 쪽에 해당 도메인을 허용하도록 구현을 해야합니다. 백엔드 개발자가 따로 있다면 백엔드 개발자에게 해당 도메인을 허용해달라고 요청을 해야겠죠. 그런데, 그럴 필요는 없습니다. 웹팩 개발서버에서 제공하는 Proxy라는 기능이 있기 때문이죠.

## proxy 설정하기

![proxy](https://imgur.com/8qNJaoI)

웹팩 개발서버의 프록시를 사용하게 되면, 브라우저 API 를 요청 할 때 백엔드 서버에 직접적으로 요청을 하지 않고, **현재 개발서버의 주소로 요청**을 하게 됩니다. 그러면 웹팩 개발 서버에서 해당 요청을 받아 그대로 백엔드 서버로 전달하고, 백엔드 서버에서 응답한 내용을 다시 브라우저쪽으로 반환합니다. 웹팩 개발서버의 proxy 설정은 원래 웹팩 설정을 통해서 적용을 하지만, CRA 를 통해 만든 리액트 프로젝트에서는 package.json 에서 "proxy" 값을 설정하여 쉽게 적용 할 수 있습니다

```json
// package.json
{
  "proxy": "http://localhost:4000"
}
```

```javascript
// api/posts.js
import axios from "axios";

// 도메인을 생략한 경우엔, 현재 페이지의 도메인을 가르키게 된다.
export const getPosts = async () => {
  const response = await axios.get("/posts");
  return response.data;
};

export const getPostById = async id => {
  const response = await axios.get(`/posts/${id}`);
  return response.data;
};
```

나중에 여러분이 프로젝트를 완성하게 되어 배포하는 경우, 리액트로 만든 서비스와 API가 동일한 도메인에서 제공이되는 경우 이대로 계속 진행을 하시면 됩니다. 하지만, 만약에 API 의 도메인과 서비스의 도메인이 다르다면 (예: 서비스는 velog.io, API 는 api.velog.io), axios 의 글로벌 baseURL 을 설정하시면 됩니다.

예를 들어 index.js 에서 다음과 같이 작성을 하시면 됩니다.

```javascript
axios.defaults.baseURL = process.env.NODE_ENV === "development" ? "/" : "https://api.velog.io/";
```

process.env.NODE_ENV는 현재 환경이 프로덕션인지 개발모드인지 볼 수 있는 값입니다

위와 같은 설정을 만약 하게 된다면, 개발 환경에선 프록시 서버 쪽으로 요청하고, 프로덕션에선 실제 API 서버로 요청을 하게 됩니다. 이러한 설정은 아까 언급한것처럼 API 도메인이 실서비스의 도메인과 다를때만 하시면 됩니다.

프로젝트를 개발 할 때 proxy 를 사용하는 것은 필수적인건 아니지만, 사용하게 되면 백엔드 쪽에서 불필요한 코드를 아낄 수 있으니 (백엔드에서 개발서버를 위한 CORS 설정을 안해도 되니까) 꽤나 유용한 기능입니다.
