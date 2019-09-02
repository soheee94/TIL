[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/
# 7. useState 를 통해 컴포넌트에서 바뀌는 값 관리하기
컴포넌트에서 보여줘야하는 내용이 사용자 인터랙션에 따라 바뀌어야 할 때!  
리액트 16.8에서 Hooks라는 기능이 도입되면서 함수형 컴포넌트에서도 상태를 관리할 수 있게 되었다. useState라는 함수를 사용하는데, 이게 바로 리액트 Hooks 중 하나이다.

## 이벤트 설정
Counter.js
```javascript
function Counter() {
  const onIncrease = () => {
    console.log('+1')
  }
  const onDecrease = () => {
    console.log('-1');
  }
  return (
    <div>
      <h1>0</h1>
      <button onClick={onIncrease}>+1</button>
      <button onClick={onDecrease}>-1</button>
    </div>
  );
}
```
리액트에서 엘리먼트에 이벤트를 설정해줄 때에는 `on이벤트이름={실행하고싶은함수}`형태로 설정해주어야 한다.

★ 주의해야할 점  
함수형태를 넣어주어야하지, 함수를 실행하면 안된다.  
onIncrease() 로 쓰면 앱이 실행되자마자 함수 호출이 되고 클릭할 때는 실행되지 않는다, 우리는 클릭 될 때만 호출되길 원하기 때문에 함수형태로 넣어주어야 한다. 또한 onClick쓸 때 click은 대문자로 써주어야한다(JSX문법).

## 동적인 값 끼얹기, useState
컴포넌트에서 동적인 값을 상태(state)라고 부른다.

Counter.js
```javascript
function Counter() {
  const [number, setNumber] = useState(0);

  const onIncrease = () => {
    setNumber(number + 1);
  }

  const onDecrease = () => {
    setNumber(number - 1);
  }

  return (
    <div>
      <h1>{number}</h1>
      <button onClick={onIncrease}>+1</button>
      <button onClick={onDecrease}>-1</button>
    </div>
  );
}
```
useState를 사용할 때에는 상태의 기본값을 파라미터로 넣어서 호출해줍니다. 이 함수를 호출해주면 배열이 반환된다. 여기서 첫번째 원소는 현재 상태, 두번째 원소는 Setter 함수이다.
파라미터로 넣은 값이 number의 기본값이다.

원래는 다음과 같이 해야하지만,
```javascript
const numberState = useState(0);
const number = numberState[0];
const setNumber = numberState[1];
```
배열 비구조화 할당을 통하여 각 원소를 추출해준것이다.

## 함수형 업데이트
지금은 Setter 함수를 사용할 때, 업데이트 하고 싶은 새로운 값을 파라미터로 넣어주고 있지만, 대신에 기존 값을 어떻게 업데이트 할 지에 대한 함수를 등록하는 방식으로도 값을 업데이트해 줄 수 있다.
```javascript
  const onIncrease = () => {
    setNumber(prevNumber => prevNumber + 1);
  }

  const onDecrease = () => {
    setNumber(prevNumber => prevNumber - 1);
  }
```
함수형 업데이트는 주로 나중에 컴포넌트를 최적화 할 때 사용한다! (나중에 알아보기)


# 8. input 상태 관리하기
InputSample.js
```javascript
function InputSample() {
  const [text, setText] = useState('');

  const onChange = (e) => {
    setText(e.target.value);
  };

  const onReset = () => {
    setText('');
  };

  return (
    <div>
      <input onChange={onChange} value={text}  />
      <button onClick={onReset}>초기화</button>
      <div>
        <b>값: {text}</b>
      </div>
    </div>
  );
}
```
이번에도 `useState`를 사용한다. onChange라는 이벤트를 사용하는데, 이밴트 객체 e를 파라미터로 받아와서 사용한다. 이 객체의 e.target은 이벤트가 발생한 DOM인 input DOM 을 가리키게 된다. 이 DOM 의 value 값, 즉 e.target.value를 조회하면 현재 input에 입력한 값이 무엇인지 알 수 있다.

★ input의 상태를 관리할 때는 input태그의 value 값도 설정해주는 것이 중요하다. 그렇게 해야 상태가 바뀌었을 때 input의 내용도 업데이트 된다!

# 9. 여러개의 input 상태 관리하기

input 의 개수가 여러개가 되었을때는, 단순히 useState 를 여러번 사용하고 onChange 도 여러개 만들어서 구현 할 수 있다. 하지만 그 방법은 가장 좋은 방법은 아니다. 더 좋은 방법은, input 에 name 을 설정하고 이벤트가 발생했을 때 이 값을 참조하는 것이다. 그리고, useState 에서는 문자열이 아니라 **객체 형태의 상태**를 관리해주어야 합니다.

InputSample.js
```javascript
function InputSample() {
  const [inputs, setInputs] = useState({
    name: '',
    nickname: ''
  });

  const { name, nickname } = inputs; // 비구조화 할당을 통해 값 추출

  const onChange = (e) => {
    const { value, name } = e.target; // 우선 e.target 에서 name 과 value 를 추출
    setInputs({
      ...inputs, // ★★ 기존의 input 객체를 복사한 뒤 (불변성)
      [name]: value // name 키를 가진 값을 value 로 설정
    });
  };

  const onReset = () => {
    setInputs({
      name: '',
      nickname: '',
    })
  };


  return (
    <div>
      <input name="name" placeholder="이름" onChange={onChange} value={name} />
      <input name="nickname" placeholder="닉네임" onChange={onChange} value={nickname}/>
      <button onClick={onReset}>초기화</button>
      <div>
        <b>값: </b>
        {name} ({nickname})
      </div>
    </div>
  );
}

```
리액트 상태에서 객체를 수정해야할 때는
```javascript
setInputs({
  ...inputs,
  [name]: value
});
```
새로운 객체를 만들어서 새로운 객체에 변화를 주고, 이를 상태로 사용해주어야 한다.
객체 key 에 대괄호를 써주면 name 키를 가진 값을 가져온다.

★★★  
이러한 작업을 **불변성을 지킨다**라고 부른다. 불변성을 지켜주어야만 리액트 컴포넌트에서 상태가 업데이트 됬음을 감지할 수 있고 이에 따라 필요한 리렌더링이 진행된다. 만약 `inputs[name] = value` 이런 식으로 기존 상태를 직접 수정하게 되면 리렌더링이 되지 않는다!!
리액트는 불변성을 지켜주어야만 컴포넌트 업데이트 성능 최적화를 제대로 할 수 있다.

> 리액트에서 객체를 업데이트 할 때는 기존 객체를 직접 수정하면 안되고, 새로운 객체를 만들어서 새!! 객체에!!! 변화를 주어야 한다!!!
