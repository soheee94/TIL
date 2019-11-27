[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 23~24. Context API 를 사용한 전역 값 관리 

우리가 현재 만들고 있는 프로젝트를 보면, App 컴포넌트에서 `onToggle`, `onRemove` 가 구현이 되어있고 이 함수들은 UserList 컴포넌트를 거쳐서 각 User 컴포넌트들에게 전달이 되고 있죠.

여기서 UserList 컴포넌트의 경우에는 `onToggle` 과 `onRemove` 를 전달하기 위하여 <u>중간 다리</u>역할만 하고 있습니다.

```javascript
function UserList({ users, onRemove, onToggle }) {
  return (
    <div>
      {users.map(user => (
        <User
          user={user}
          key={user.id}
          onRemove={onRemove} //User로 전달 
          onToggle={onToggle}
        />
      ))}
    </div>
  );
}
```
지금과 같이 특정 함수를 특정 컴포넌트를 거쳐서 원하는 컴포넌트에게 전달하는 작업은 리액트로 개발을 하다보면 자주 발생 할 수 있는 작업인데요, 위와 같이 컴포넌트 한개정도를 거쳐서 전달하는건 사실 그렇게 큰 불편함도 없지만, 만약 3~4개 이상의 컴포넌트를 거쳐서 전달을 해야 하는 일이 발생하게 된다면 이는 매우 번거로울 것 입니다.

그럴 땐, 리액트의 `Context API` 와 이전 섹션에서 배웠던 `dispatch` 를 함께 사용하면 이러한 복잡한 구조를 해결 할 수 있습니다.

리액트의 Context API 를 사용하면, <mark>프로젝트 안에서 전역적으로 사용 할 수 있는 값을 관리</mark> 할 수 있습니다.

Context API 를 사용해여 새로운 Context 를 만드는 방법을 알아보겠습니다.

Context 를 만들 땐 다음과 같이 `React.createContext()` 라는 함수를 사용합니다.

```javascript
const UserDispatch = React.createContext(null);
```

`createContext` 의 파라미터에는 Context 의 <mark>기본값</mark>을 설정할 수 있습니다. 여기서 설정하는 값은 Context 를 쓸 때 값을 따로 지정하지 않을 경우 사용되는 기본 값 입니다. > Null

Context 를 만들면, Context 안에 `Provider` 라는 컴포넌트가 들어있는데 이 컴포넌트를 통하여 <u>Context 의 값</u>을 정할 수 있습니다. 이 컴포넌트를 사용할 때, `value` 라는 값을 설정해주면 됩니다.

```javascript
<UserDispatch.Provider value={dispatch}>
    <Component></Component>
</UserDispatch.Provider>
```

이렇게 설정해주고 나면 <u>Provider 에 의하여 감싸진 컴포넌트 중 <mark>어디서든지</mark> 우리가 Context 의 값을 다른 곳에서 바로 조회해서 사용 할 수 있습니다.</u> 

`UserDispatch` 를 만들 때 다음과 같이 내보내주는 작업을 한다.
```javascript
// App.js
export const UserDispatch = React.createContext(null);
```

이렇게 내보내주면 나중에 사용하고 싶을 때 다음과 같이 불러와서 사용 할 수 있습니다.
```javascript
import { UserDispatch } from './App';
```

```javascript
// App.js
export const UserDispatch = createContext(null);
// 기본값은 필요 없기 때문에 null 사용
function App() {
  // 생략
  const [state, dispatch] = useReducer(reducer, initialState);
  // 생략
  return (
    // dispatch 를 값으로 전달
    <UserDispatch.Provider value={dispatch}> 
      <CreateUser username={username} email={email} onChange={onChange} onCreate={onCreate}/>
      <UserList users={users}/>
      <div>활성 사용자 수 : {count}</div>
    </UserDispatch.Provider>
  );
}
```

User 컴포넌트에서 바로 `dispatch` 를 사용 할건데요, 그렇게 하기 위해서는 `useContext` 라는 Hook 을 사용해서 우리가 만든 UserDispatch Context 를 조회해야합니다.

```javascript
// UserList.js

import React, { useContext } from 'react';
import { UserDispatch } from './App'; //1. 불러오고

const User = React.memo(function User({ user }) {
const dispatch = useContext(UserDispatch); //2. Dispatch 사용하겠다!

  return (
    <div>
      <b
        style={{
          cursor: 'pointer',
          color: user.active ? 'green' : 'black'
        }}
        // 3. dispatch로 TOGGLE_USER type 전달
        onClick={() => {
          dispatch({ type: 'TOGGLE_USER', id: user.id });
        }}
      >
        {user.username}
      </b>
      &nbsp;
      <span>({user.email})</span>
      <button
        // 3. dispatch로 REMOVE_USER type 전달
        onClick={() => {
          dispatch({ type: 'REMOVE_USER', id: user.id });
        }}
      >
        삭제
      </button>
    </div>
  );
});

function UserList({ users }) {
  return (
    // > ★ ★ UserList 를 거치지 않아도 onRemove, onToggle 사용 가능
    <div>
      {users.map(user => (
        <User user={user} key={user.id} />
      ))}
    </div>
  );
}

export default React.memo(UserList);
```

이렇게 Context API 를 사용해서 `dispatch` 를 어디서든지 조회해서 사용해줄 수 있게 해주면 코드의 구조가 훨씬 깔끔해질 수 있습니다.

---

이로써 `useState` 를 사용하는 것과 `useReducer` 를 사용하는 것의 큰 차이를 발견했지요? `useReducer` 를 사용하면 이렇게 `dispatch` 를 Context API 를 사용해서 <mark>전역적으로 사용</mark> 할 수 있게 해주면 컴포넌트에게 함수를 전달해줘야 하는 상황에서 코드의 구조가 훨씬 깔끔해질 수 있습니다.

만약에 깊은 곳에 위치하는 컴포넌트에게 여러 컴포넌트를 거쳐서 함수를 전달해야 하는 일이 있다면 이렇게 Context API 를 사용하시면 됩니다.
