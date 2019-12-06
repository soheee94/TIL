[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 3. useAsync 커스텀 Hook 만들어서 사용하기

데이터를 요청해야 할 때마다 리듀서를 작성하는 것은 번거로운 일 입니다. 매번 반복되는 코드를 작성하는 대신에, <u>커스텀 Hook 을 만들어서 요청 상태 관리 로직을 쉽게 재사용하는 방법</u>을 알아봅시다.

```javascript
// useAsync.js
import React, { useReducer, useEffect } from "react";

function reducer(state, action) {
  switch (action.type) {
    case "LOADING":
      return {
        loading: true,
        data: null,
        error: null
      };
    case "SUCCESS":
      return {
        loading: false,
        data: action.data,
        error: null
      };
    case "ERROR":
      return {
        loading: true,
        data: null,
        error: action.error
      };
    default:
      throw new Error(`Unhandled action type ${action.type}`);
  }
}

// skip : 데이터 나중에 불러오기 위한 파라미터
// true이면 불러오지 않는다.
function Userasync(callback, deps = [], skip = false) {
  const [state, dispatch] = useReducer(reducer, {
    loading: false,
    data: null,
    error: false
  });

  const fetchData = async () => {
    dispatch({ type: "LOADING" });
    try {
      const data = await callback();
      dispatch({ type: "SUCCESS", data });
    } catch (e) {
      dispatch({ type: "ERROR", error: e });
    }
  };

  useEffect(() => {
    if (skip) return;
    fetchData();
  }, deps);

  return [state, fetchData];
}

export default Userasync;
```

```javascript
// Users.js
import React, { useState } from "react";
import axios from "axios";
import Userasync from "./Userasync";
import User from "./User";

async function getUsers() {
  const response = await axios.get(
    "https://jsonplaceholder.typicode.com/users"
  );
  return response.data;
}

function Users() {
  const [userId, setUserId] = useState(null);
  const [state, refetch] = Userasync(getUsers, [], true);
  const { loading, data: users, error } = state;
  if (loading) return <div>로딩중!</div>;
  if (error) return <div>에러 발생!</div>;
  if (!users) return <button onClick={refetch}>불러오기</button>;

  return (
    <>
      <ul>
        {users.map(user => (
          <li key={user.id} onClick={() => setUserId(user.id)}>
            {user.name}({user.username})
          </li>
        ))}
      </ul>
      <button onClick={refetch}>다시 불러오기</button>
      {userId && <User id={userId} />}
    </>
  );
}

export default Users;
```

## API 에 파라미터가 필요한 경우

```javascript
// User.js
import React from "react";
import axios from "axios";
import Userasync from "./Userasync";

async function getUser(id) {
  const response = await axios.get(
    `https://jsonplaceholder.typicode.com/users/${id}`
  );

  return response.data;
}

function User({ id }) {
  const [state] = Userasync(() => getUser(id), [id]);
  const { loading, data: user, error } = state;

  if (loading) return <div>로딩중</div>;
  if (error) return <div>에러 발생</div>;
  if (!user) return null;
  return (
    <div>
      <h2>{user.name}</h2>
      <p>{user.email}</p>
    </div>
  );
}

export default User;
```
