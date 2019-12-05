[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 1. API 연동의 기본

API 를 호출하기 위해서 `axios` 라는 라이브러리 설치

```cmd
$ yarn add axios
```

`axios`를 사용해서 GET, PUT, POST, DELETE 등의 메서드로 API 요청을 할 수 있는데요, 만약 이 메서드들에 대하여 잘 모르시는 경우에는 [REST API](https://meetup.toast.com/posts/92) 에 대한 글을 한번 읽어보세요.

REST API 를 사용 할 때에는 하고 싶은 작업에 따라 다른 메서드로 요청을 할 수 있는데 메서드들은 다음 의미를 가지고 있습니다.

- GET: 데이터 조회
- POST: 데이터 등록
- PUT: 데이터 수정
- DELETE: 데이터 제거

이 메서드 외에도 PATCH, HEAD 와 같은 메서드들도 있습니다.

axios 의 사용법은 다음과 같습니다.

```javascript
import axios from "axios";
axios.get("/users/1");
```

get 이 위치한 자리에는 메서드 이름을 소문자로 넣습니다. 예를 들어서 새로운 데이터를 등록하고 싶다면 axios.post() 를 사용하면 됩니다.

그리고, 파라미터에는 API 의 주소를 넣습니다.

axios.post() 로 데이터를 등록 할 때에는 두번째 파라미터에 등록하고자 하는 정보를 넣을 수 있습니다.

```javascript
axios.post("/users", {
  username: "blabla",
  name: "blabla"
});
```

[JSON Placeholder](https://jsonplaceholder.typicode.com/)사용

## useState와 useEffect로 데이터 로딩하기

`useState` 를 사용하여 요청 상태를 관리하고, `useEffect` 를 사용하여 컴포넌트가 렌더링되는 시점에 요청을 시작하는 작업을 해보겠습니다.

요청에 대한 상태를 관리 할 때에는 다음과 같이 총 3가지 상태를 관리해주어야합니다.

1. 요청의 결과
2. 로딩 상태
3. 에러

```javascript
// Users.js
import React, { useEffect, useState } from "react";
import axios from "axios";

function Users() {
  const [users, setUsers] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchUsers = async () => {
    try {
      // 1. 초기화
      setError(null);
      setUsers(null);
      // 2. 로딩 시작
      setLoading(true);
      const response = await axios.get(
        "https://jsonplaceholder.typicode.com/users"
      );
      // 3. 사용자 데이터 설정
      setUsers(response.data);
    } catch (e) {
      setError(e);
    }
    setLoading(false);
  };

  useEffect(() => {
    // useEffect 첫번째 파라미터로 등록하는 함수에서는 async 사용 불가
    // 내부에서 사용할 때는 함수 선언하여 사용
    // const fetchUsers = async () => {
    //   try {
    //     // 1. 초기화
    //     setError(null);
    //     setUsers(null);
    //     // 2. 로딩 시작
    //     setLoading(true);
    //     const response = await axios.get(
    //       "https://jsonplaceholder.typicode.com/users"
    //     );
    //     // 3. 사용자 데이터 설정
    //     setUsers(response.data);
    //   } catch (e) {
    //     setError(e);
    //   }
    //   setLoading(false);
    // };

    fetchUsers();
  }, []);

  if (loading) return <div>로딩중!</div>;
  if (error) return <div>에러 발생!</div>;
  if (!users) return null;

  return (
    <>
      <ul>
        {users.map(user => (
          <li key={user.id}>
            {user.name}({user.username})
          </li>
        ))}
      </ul>
      <button onClick={fetchUsers}>다시 불러오기</button>
    </>
  );
}

export default Users;
```
