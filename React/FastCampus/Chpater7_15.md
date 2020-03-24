[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 8. json-server

## 가짜 API 서버 열기

프로젝트 디렉터리에 (src 디렉터리 밖에) data.json 이라는 파일 만들기

```json
// data.json
{
  "posts": [
    {
      "id": 1,
      "title": "리덕스 미들웨어를 배워봅시다",
      "body": "리덕스 미들웨어를 직접 만들어보면 이해하기 쉽죠."
    },
    {
      "id": 2,
      "title": "redux-thunk를 사용해봅시다",
      "body": "redux-thunk를 사용해서 비동기 작업을 처리해봅시다!"
    },
    {
      "id": 3,
      "title": "redux-saga도 사용해봅시다",
      "body": "나중엔 redux-saga를 사용해서 비동기 작업을 처리하는 방법도 배워볼 거예요."
    }
  ]
}
```

서버 열기

```cmd
$ npx json-server ./data.json --port 4000
```

## axios를 사용하여 API 호출하기

```javascript
// api/posts.js
import axios from "axios";

export const getPosts = async () => {
  const response = await axios.get("http://localhost:4000/posts");
  return response.data;
};

export const getPostById = async id => {
  const response = await axios.get(`http://localhost:4000/posts/${id}`);
  return response.data;
};
```
