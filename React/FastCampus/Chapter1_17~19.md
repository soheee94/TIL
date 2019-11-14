[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 17. useMemo 를 사용하여 연산한 값 재사용하기

**성능 최적화**를 위하여 연산된 값을 `useMemo`라는 Hook 을 사용하여 재사용하는 방법을 알아본다.

```javascript
//App.js
function countActiveUsers(users) {
  console.log('활성 사용자 수를 세는중...');
  return users.filter(user => user.active).length;
}
// 생략
const count = countActiveUsers(users);
```
여기서 발생하는 성능적 문제는 <mark>input 의 값을 바꿀때</mark>에도 countActiveUsers 함수가 호출된다는 것 입니다.

활성 사용자 수를 세는건, users 에 변화가 있을때만 세야되는건데, input 값이 바뀔 때에도 컴포넌트가 리렌더링 되므로 이렇게 불필요할때에도 호출하여서 자원이 낭비되고 있습니다.

이러한 상황에는 `useMemo` 라는 Hook 함수를 사용하면 성능을 최적화 할 수 있습니다.

Memo 는 "memoized" 를 의미하는데, 이는, 이전에 계산 한 값을 재사용한다는 의미를 가지고 있습니다.

```javascript
const count = useMemo(() => countActiveUsers(users), [users]);
```
`useMemo` 의 첫번째 파라미터에는 어떻게 연산할지 정의하는 함수를 넣어주면 되고 두번째 파라미터에는 deps 배열을 넣어주면 되는데, 이 배열 안에 넣은 내용이 바뀌면, 우리가 등록한 함수를 호출해서 값을 연산해주고, 만약에 내용이 바뀌지 않았다면 이전에 연산한 값을 재사용하게 됩니다. (useEffect 와 같은 원리)

# 18. useCallback 을 사용하여 함수 재사용하기

`useCallback` 은 우리가 지난 시간에 배웠던 `useMemo` 와 비슷한 Hook 입니다.

`useMemo` 는 <u>특정 결과값</u>을 재사용 할 때 사용하는 반면, `useCallback` 은 <u>특정 함수</u>를 새로 만들지 않고 재사용하고 싶을때 사용합니다.

이전에 App.js 에서 구현했었던 onCreate, onRemove, onToggle 함수를 확인해봅시다. 이 함수들은 컴포넌트가 리렌더링 될 때 마다 새로 만들어집니다. 함수를 선언하는 것 자체는 사실 메모리도, CPU 도 리소스를 많이 차지 하는 작업은 아니기 때문에 함수를 새로 선언한다고 해서 그 자체 만으로 큰 부하가 생길일은 없지만, 한번 만든 함수를 필요할때만 새로 만들고 재사용하는 것은 여전히 중요합니다.

그 이유는, 우리가 나중에 컴포넌트에서 props 가 바뀌지 않았으면 Virtual DOM 에 새로 렌더링하는 것 조차 하지 않고 컴포넌트의 결과물을 재사용 하는 최적화 작업을 할건데요, 이 작업을 하려면, 함수를 재사용하는것이 필수입니다.

```javascript
const onCreate = useCallback(() => {
    const user = {
      id: nextId.current,
      username,
      email
    };
    setUsers(users.concat(user));

    setInputs({
      username: '',
      email: ''
    });
    nextId.current += 1;
  }, [users, username, email]);
```

★★ 주의 하실 점은, 함수 안에서 사용하는 상태 혹은 `props` 가 있다면 꼭, `deps` 배열안에 포함시켜야 된다는 것 입니다. 만약에 deps 배열 안에 함수에서 사용하는 값을 넣지 않게 된다면, 함수 내에서 해당 값들을 참조할때 가장 최신 값을 참조 할 것이라고 보장 할 수 없습니다. props 로 받아온 함수가 있다면, 이 또한 deps 에 넣어주어야 해요. (useEffect(마운트/언마운트), useMemo(특정 연산값)와 동일)

> Chrome Extension - React DevTools 설치

# 19. React.memo 를 사용한 컴포넌트 리렌더링 방지

컴포넌트의 `props` 가 바뀌지 않았다면, 리렌더링을 방지하여 컴포넌트의 리렌더링 성능 최적화를 해줄 수 있는 React.memo 라는 함수에 대해서 알아보겠습니다.

사용법은 그냥 감싸주기만 하면 된다.

```javascript
export default React.memo(CreateUser);
```

그런데, User 중 하나라도 수정하면 모든 User 들이 리렌더링되고, CreateUser 도 리렌더링이 됩니다.

이유는 간단합니다. users 배열이 바뀔때마다 onCreate 도 새로 만들어지고, onToggle,onRemove 도 새로 만들어지기 때문입니다.

=> useCallback deps에 users가 들어가 있기 때문에 

그렇다면! 이걸 최적화하고 싶다면 어떻게해야 할까요? 바로 deps 에서 `users` 를 지우고, <u>함수들에서 현재 useState 로 관리하는 users 를 참조하지 않게 하는것입니다.</u> 함수형 업데이트를 하게 되면, setUsers 에 등록하는 콜백함수의 파라미터에서 최신 users 를 참조 할 수 있기 때문에 deps 에 users 를 넣지 않아도 된답니다.

```javascript
const onToggle = useCallback(id => {
    setUsers(users =>
      users.map(user =>
        user.id === id ? { ...user, active: !user.active } : user
      )
    );
  }, []);
```

리액트 개발을 하실 때, useCallback, useMemo, React.memo 는 <mark>컴포넌트의 성능을 실제로 개선할수있는 상황에서만 하세요.</mark>

★예를 들어서, User 컴포넌트에 `b` 와 `button` 에 `onClick` 으로 설정해준 함수들은, 해당 함수들을 `useCallback` 으로 재사용한다고 해서 리렌더링을 막을 수 있는것은 아니므로, 굳이 그렇게 할 필요 없습니다.

추가적으로, 렌더링 최적화 하지 않을 컴포넌트에 React.memo 를 사용하는것은, 불필요한 props 비교만 하는 것이기 때문에 실제로 렌더링을 방지할수있는 상황이 있는 경우에만 사용하시길바랍니다.

추가적으로, React.memo 에서 두번째 파라미터에 propsAreEqual 이라는 함수를 사용하여 특정 값들만 비교를 하는 것도 가능합니다.

```javascript
export default React.memo(
  UserList,
  (prevProps, nextProps) => prevProps.users === nextProps.users
);
```

하지만, 이걸 잘못사용한다면 오히려 의도치 않은 버그들이 발생하기 쉽습니다. 예를 들어서, 함수형 업데이트로 전환을 안했는데 이렇게 users 만 비교를 하게 된다면, onToggle 과 onRemove 에서 최신 users 배열을 참조하지 않으므로 심각한 오류가 발생 할 수 있습니다.