[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 2. useReducer로 요청상태 관리하기

```javascript
// Users.js
import React, { useEffect, useReducer } from "react";
import axios from "axios";

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
        loading: false,
        data: null,
        error: action.error
      };
    default:
      throw new Error(`unhandled action type : ${action.type}`);
  }
}

function Users() {
  const [state, dispatch] = useReducer(reducer, {
    loading: false,
    data: null,
    error: null
  });
  const fetchUsers = async () => {
    try {
      // 1. 초기화 / 로딩 시작
      dispatch({ type: "LOADING" });
      const response = await axios.get(
        "https://jsonplaceholder.typicode.com/users"
      );
      // 2. 사용자 데이터 설정
      dispatch({ type: "SUCCESS", data: response.data });
    } catch (e) {
      dispatch({ type: "ERROR", error: e });
    }
    // setLoading(false);
  };

  useEffect(() => {
    fetchUsers();
  }, []);

  const { loading, data: users, error } = state;
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

`useReducer` 로 구현했을 때의 장점은 `useState` 의 `setState` 함수를 여러번 사용하지 않아도 된다는점과, <u>리듀서로 로직을 분리했으니 다른곳에서도 쉽게 재사용을 할 수 있다는 점 입니다.</u>
