[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 4. react-async 로 요청 상태 관리하기

[react-async](https://github.com/async-library/react-async) : 요청 상태 관리를 위한 라이브러리(지난 섹션에서 만들었던 `useAsync` 와 비슷한 함수가 들어있음)

설치

```cmd
$ yarn add react-async
```

공식 사용법

```javascript
import { useAsync } from "react-async";

const loadCustomer = async ({ customerId }, { signal }) => {
  const res = await fetch(`/api/customers/${customerId}`, { signal });
  if (!res.ok) throw new Error(res);
  return res.json();
};

const MyComponent = () => {
  const { data, error, isLoading } = useAsync({
    promiseFn: loadCustomer,
    customerId: 1
  });
  if (isLoading) return "Loading...";
  if (error) return `Something went wrong: ${error.message}`;
  if (data)
    return (
      <div>
        <strong>Loaded some data:</strong>
        <pre>{JSON.stringify(data, null, 2)}</pre>
      </div>
    );
  return null;
};
```

react-async 의 `useAsync` 를 사용 할 때 파라미터로 넣는 옵션 객체에는 호출 할 함수 `promiseFn` 을 넣고, 파라미터도 필드 이름과 함께 `(customerId)` 넣어주어야 합니다.

> 이전 커스텀 hook은 배열 형태로 반환했지만, 이 라이브러리는 객체 형태로 반환!

## User 컴포넌트 전환

```javascript
// User.js
import React from "react";
import axios from "axios";
// 라이브러리 사용
import { useAsync } from "react-async";

// 파라미터 객체형태로 반환
async function getUser({ id }) {
  const response = await axios.get(
    `https://jsonplaceholder.typicode.com/users/${id}`
  );

  return response.data;
}

// watch 값을 넣어주면 이 값이 바뀔때마다 함수를 다시 호출!!!
function User({ id }) {
  const { isLoading, data: user, error } = useAsync({
    promiseFn: getUser,
    id,
    watch: id
  });

  if (isLoading) return <div>로딩중</div>;
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

## Users 컴포넌트 전환

```javascript
// Users.js

import React, { useState } from "react";
import axios from "axios";
import User from "./User";
import { useAsync } from "react-async";

async function getUsers() {
  const response = await axios.get(
    "https://jsonplaceholder.typicode.com/users"
  );
  return response.data;
}

function Users() {
  const [userId, setUserId] = useState(null);
  const { isLoaing, data: users, error, run } = useAsync({
    deferFn: getUsers
  });

  if (isLoaing) return <div>로딩중!</div>;
  if (error) return <div>에러 발생!</div>;
  if (!users) return <button onClick={run}>불러오기</button>;

  return (
    <>
      <ul>
        {users.map(user => (
          <li key={user.id} onClick={() => setUserId(user.id)}>
            {user.name}({user.username})
          </li>
        ))}
      </ul>
      <button onClick={run}>다시 불러오기</button>
      {userId && <User id={userId} />}
    </>
  );
}

export default Users;
```

## 장점, 단점

react-async 라이브러리는 정말 쓸만하고, 편합니다. 다만, 우리가 이전에 직접 만들었던 useAsync 와 크게 다를 건 없죠. 어떤 측면에서는 우리가 직접 만든 Hook 이 편하기도 합니다. 예를 들어서 Hook 의 옵션이 굉장히 간단하죠. 그리고, watch 같은 것 대신에 deps 를 사용하기도 하고, 반환 값이 배열 형태이기 때문에 (리액트 자체 내장 Hook 과 사용성이 비슷하다는 측면에서) 더욱 Hook 스럽습니다.

반면에 react-async 의 `useAsync` 는 옵션이 다양하고 (promiseFn, deferFn, watch, ...) 결과 값도 객체 안에 다양한 값이 들어있어서 (run, reload, ...) 헷갈릴 수 있는 단점이 있긴 하지만 다양한 기능이 이미 내장되어있고 (예를 들어서 요청을 취소 할 수도 있습니다.) Hook 을 직접 만들 필요 없이 바로 불러와서 사용 할 수 있는 측면에서는 정말 편합니다.

그렇다면 과연 Hook 을 직접 만들어서 써야 할까요 아니면 라이브러리로 불러와서 사용 해야 할까요? 정해진 답은 없습니다.

만약 우리가 직접 만들었던 useAsync 의 작동 방식을 완벽히 이해하셨다면 여러분의 필요에 따라 커스터마이징 해가면서 사용 할 수 있으니까 직접 만들어서 사용하는 것을 추천드립니다. 특히나, 연습용 프로젝트가 아니라, 오랫동안 유지보수 할 수도 있게 되는 프로젝트라면 더더욱 추천합니다.

반면, 작은 프로젝트이거나, 직접 만든 useAsync 의 작동 방식이 조금 어렵게 느껴지신다면 라이브러리로 설치해서 사용하는것도 좋습니다.

case by case!!!!
