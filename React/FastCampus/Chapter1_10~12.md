[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 10. useRef로 특정 DOM 선택하기
JS에서는 특정 DOM을 선택해야하는 상황에 getElementById, querySelector 같은 DOM Selector 함수를 사용해서 DOM을 선택한다.
리액트를 사용하는 프로젝트에서는 `ref` 라는 것을 사용한다.

함수형 컴포넌트에서 ref를 사용할 때에는 useRef라는 Hook 함수를 사용한다. 클래스형 컴포넌트에서는 콜백함수를 사용하거나 React.createRef 라는 함수를 사용한다. 

InputSample에서 초기화 버튼을 눌렀을 때, 이름 input에 포커스가 잡히도록 useRef를 통해 구현해본다!

InputSample.js
```javascript
/// 함수형 컴포넌트
function InputSample() {
  const [inputs, setInputs] = useState({
    name: '',
    nickname: ''
  });
  
  const nameInput = useRef();
  const { name, nickname } = inputs; // 비구조화 할당을 통해 값 추출

  // 생략 onChange

  const onReset = () => {
    setInputs({
      name: '',
      nickname: ''
    });
    nameInput.current.focus();
  };

  return (
    <div>
      <input
        name="name"
        placeholder="이름"
        onChange={onChange}
        value={name}
        ref={nameInput}
      />
      <input
        name="nickname"
        placeholder="닉네임"
        onChange={onChange}
        value={nickname}
      />
      <button onClick={onReset}>초기화</button>
      <div>
        <b>값: </b>
        {name} ({nickname})
      </div>
    </div>
  );
}
```

useRef를 사용하여 Ref객체를 만들고, 이 객체를 우리가 선택하고 싶은 DOM에 ref값으로 설정해 주어야 한다. 그러면 Ref 객체의 .current 값은 우리가 원하는 DOM 을 가르키게 된다.

위 예제에서는 onReset 함수에서 input에 포커스를 하는 focus() DOM API 를 호출해주었다.

# 11. 배열 렌더링하기

UserList.js
```javascript
function User({ user }) {
  // user 는 props (Chapter1_5.md)
  return (
    <div>
      <b>{user.username}</b> <span>({user.email})</span>
    </div>
  );
}

function UserList() {
  const users = [
    {
      id: 1,
      username: 'velopert',
      email: 'public.velopert@gmail.com'
    },
    {
      id: 2,
      username: 'tester',
      email: 'tester@example.com'
    },
    {
      id: 3,
      username: 'liz',
      email: 'liz@example.com'
    }
  ];
   
  return (
    <div>
      {users.map(user => (
        <User user={user} />
      ))}
    </div>
  );
}
```

`map` 함수는 배열안에 있는 각 원소를 변환하여 새로운 배열을 만들어준다! 리액트에서 동적인 배열을 렌더링 할 때에는 이 함수를 사용하여 일반 데이터 배열을 리액트 엘리먼트로 이루어진 배열로 변환해주면 된다.

이 상태로 코드를 실행하면 콘솔창에
> Warning: Each child in a list should have a unique "key" prop.  

이런 오류가 발생한다!
리액트에서 배열을 렌더링 할 때는 key 라는 props를 설정해야 한다. key 값은 각 원소들마다 가지고 있는 고유 값으로 설정한다. 지금은 id가 고유 값이다.

```javascript
<div>
    {users.map(user => (
    <User user={user} key={user.id} />
    ))}
</div>
```
만약 배열 안에 고유한 값이 없다면 map 함수의 콜백함수 두번째 파라미터 index를 key로 사용하면 된다.
```javascript
<div>
  {users.map((user, index) => (
    <User user={user} key={index} />
  ))}
</div>
```
그러나 index는 왠만하면 사용하지 않는 것이 좋다!
배열을 렌더링할 때 key 설정을 하지 않게 된다면 배열이 업데이트 될 때 효율적으로 렌더링 될 수 없다.  
배열 삭제 / 삽입할 때 매우 비효율적이다.

# 12. useRef로 컴포넌트 안의 변수 만들기
`useRef` Hook은 DOM을 선택하는 용도 외에도, 컴포넌트 안에서 **조회 및 수정할 수 있는 변수를 관리하는 것**이다.

useRef로 관리하는 변수는 값이 바뀐다고 해서 컴포넌트가 ★<u>리렌더링 되지 않는다.</u>

?!
리액트 컴포넌트에서의 상태는 상태를 바꾸는 함수를 호출하고 나서 그 다음 렌더링 이후로 업데이트 된 상태를 조회할 수 있는 반면, useRef로 관리하고 있는 변수는 설정 후 바로 조회할 수 있다. 

(`useState`는 리렌더링 되어 진다.)

useRef로 관리할 수 있는 값(리렌더링하지 않고도 값을 관리하고 싶을 때)
* setTimeout, setInterval 을 통해서 만들어진 id
* 외부 라이브러리를 사용하여 생성된 instance
* scroll 위치

App.js
```javascript
function App() {
  const users = [
    {
      id: 1,
      username: 'velopert',
      email: 'public.velopert@gmail.com'
    },
    {
      id: 2,
      username: 'tester',
      email: 'tester@example.com'
    },
    {
      id: 3,
      username: 'liz',
      email: 'liz@example.com'
    }
  ];

  const nextId = useRef(4);
  const onCreate = () => {
    // 나중에 구현 할 배열에 항목 추가하는 로직
    // ...
    console.log(nextId.current); //4
    nextId.current += 1;
  };
  return <UserList users={users} />;
}

```