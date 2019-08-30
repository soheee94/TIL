[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 05. props를 통해 컴포넌트에게 값 전달하기

props는 properties의 줄임말이다. 우리가 어떤 값을 컴포넌트에 넘겨줘야할 때, props를 사용한다. (부모 > 자식)

## props의 기본 사용법
App.js
```javascript
function App() {
  return (
    <Hello name="react" />
  );
}
```
Hello.js
```javascript
function Hello(props) {
  return <div>안녕하세요 {props.name}</div>
}
```
컴포넌트에게 전달되는 props는 파라미터를 통하여 조회할 수 있다. props는 객체 형태로 전달되며, name값을 조회하고 싶다면 `props.name`을 조회하면 된다.

App.js > Hello.js 로 전달

## 여러개의 props, 비구조화 할당
App.js
```javascript
<Hello name="react" color="red"/>
```
Hello.js
```javascript
function Hello(props) {
  return <div style={{ color: props.color }}>안녕하세요 {props.name}</div>
}
```
props 내부의 값을 조회할 때마다 props.를 사용하는데, 비구조화 할당을 사용하여 간결하게 표현한다.
```javascript
function Hello({ color, name }) {
  return <div style={{ color }}>안녕하세요 {name}</div>
}
```
## defaultProps로 기본값 설정
컴포넌트에 props를 지정하지 않았을 때, 기본적으로 사용할 값을 설정할 수 있다.

```javascript
Hello.defaultProps = {
  name: '이름없음'
}
```

## props.children
컴포넌트 태그 사이에 넣은 값을 조회하고 싶을 땐, props.children을 조회하면 된다.

Wrapper.js
```javascript
function Wrapper() {
  const style = {
    border: '2px solid black',
    padding: '16px',
  };
  return (
    <div style={style}>

    </div>
  )
}
```
App.js
```javascript
function App() {
  return (
    <Wrapper>
      <Hello name="react" color="red"/>
      <Hello color="pink"/>
    </Wrapper>
  );
}
```
이 상태로 브라우저를 확인하면 Hello 컴포넌트 두 개가 보이지 않는다.

wrapper에서 return할 때 `<div></div>`만 return하기 때문에 Hello들은 사라지게 된다! 그래서 내부의 내용이 보여지게 하기 위해서는 wrapper에서 props.children을 렌더링 해주어야한다.
```javascript
function Wrapper({ children }) {
  const style = {
    border: '2px solid black',
    padding: '16px',
  };
  return (
    <div style={style}>
      {children}
    </div>
  )
}
```
