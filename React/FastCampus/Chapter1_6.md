[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 6. 조건부 렌더링
조건부 렌더링이란, <u>특정 조건에 따라 다른 결과물을 렌더링 하는 것</u>을 의미한다.
```javascript
function App() {
  return (
    <Wrapper>
      <Hello name="react" color="red" isSpecial={true}/>
      <Hello color="pink" />
    </Wrapper>
  )
}
```
true는 자바스크립트 값이기 때문에 중괄호로 감싸주어야한다.
Hello 컴포넌트에서 isSpecial이 true / false 인지에 따라 값을 변화하게 하기 위한 기본적인 방법은 **삼항연산자**를 사용하는 것이다.

```javascript
function Hello({ color, name, isSpecial }) {
  return (
    <div style={{ color }}>
      { isSpecial ? <b>*</b> : null }
      안녕하세요 {name}
    </div>
  );
}
```

보통 삼항연산자를 사용한 조건부 렌더링은 주로 특정 조건에 따라 보여줘야하는 내용이 다를 때 사용한다. 

지금은 내용이 달라지는 것이 아니라, true 이면 보여주고, 그렇지 않다면 null처리 하고 있기 띠문에 `&&`연산자를 사용해서 처리하는 것이 더 간편하다.
```javascript
function Hello({ color, name, isSpecial }) {
  return (
    <div style={{ color }}>
      {isSpecial && <b>*</b>}
      안녕하세요 {name}
    </div>
  );
}
```
[참고] 단축 평가 논리 계산법

props값 설정을 생략한다면, 이를 `true`로 설정한 것으로 간주한다.
```javascript
function App() {
  return (
    <Wrapper>
      <Hello name="react" color="red" isSpecial />
      <Hello color="pink"/>
    </Wrapper>
  );
}
```
이 때, isSpecial은 true이다!