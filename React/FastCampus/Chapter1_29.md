[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 29.  LifeCycle Method

LifeCycle Method 는 한국어로 **"생명주기 메서드"** 라고 부른다. 생명주기 메서드는 컴포넌트가 브라우저상에 나타나고, 업데이트되고, 사라지게 될 때 호출되는 메서드들 입니다. 추가적으로 컴포넌트에서 에러가 났을 때 호출되는 메서드도 있습니다.

생명주기 메서드는 **클래스형 컴포넌트에서만** 사용 할 수 있는데요, 우리가 기존에 배웠었던 `useEffect` 랑 은근히 비슷하다고 생각하시면 됩니다. (물론 작동방식은 많이 다릅니다. 커버하지 않는 기능들도 있습니다.)

앞으로 사용할 일은 별로 없지만 [매뉴얼](https://ko.reactjs.org/docs/react-component.html) 보고 사용할 수 있으면 된다.

[프로젝트 예제](https://codesandbox.io/s/currying-bash-mrkjb?fontsize=14)

위 프로젝트에서는 총 3가지 버튼이 있습니다.

- "랜덤 색상" 버튼을 누르면 숫자의 색상이 바뀝니다.
- "토글" 버튼을 누르면 컴포넌트가 사라지거나 나타납니다.
- "더하기" 버튼을 누르면 숫자가 1씩 더해집니다.

이렇게 변화가 발생 할 때마다 생명주기 메서드들을 호출하게 된다  
![LifeCycle](https://i.imgur.com/cNfpEph.png)
출처: http://projects.wojtekmaj.pl/react-lifecycle-methods-diagram/

## 마운트

마운트 생명주기
- constructor
- getDerivedStateFromProps
- render
- componentDidMount

### constructor

`constructor` 는 컴포넌트의 생성자 메서드이다. 컴포넌트가 만들어지면 가장 먼저 실행되는 메서드!

```javascript
constructor(props) {
    super(props);
    console.log("constructor");
}
```

### getDerivedStateFromProps

`getDerivedStateFromProps` 는 `props` 로 받아온 것을 `state` 에 넣어주고 싶을 때 사용합니다.

```javascript
  static getDerivedStateFromProps(nextProps, prevState) {
    console.log("getDerivedStateFromProps");
    if (nextProps.color !== prevState.color) {
      return { color: nextProps.color };
    }
    return null;
  }
```

다른 생명주기 메서드와는 달리 앞에 `static` 을 필요로 하고, 이 안에서는 `this` 롤 조회 할 수 없습니다. 여기서 <u>특정 객체를 반환하게 되면 해당 객체 안에 있는 내용들이 컴포넌트의 state 로 설정이 됩니다.</u> 반면 null 을 반환하게 되면 아무 일도 발생하지 않습니다.

★
참고로 이 메서드는 컴포넌트가 처음 렌더링 되기 전에도 호출 되고, 그 이후 리렌더링 되기 전에도 매번 실행됩니다.

### render

컴포넌트를 렌더링하는 메서드

### componentDidMount

컴포넌트의 첫번째 렌더링이 마치고 나면 호출되는 메서드입니다. <u>이 메서드가 호출되는 시점에는 우리가 만든 컴포넌트가 화면에 나타난 상태입니다.</u>   
여기선 주로 D3, masonry 처럼 **DOM 을 사용해야하는 외부 라이브러리 연동**을 하거나, 해당 컴포넌트에서 필요로하는 데이터를 요청하기 위해 axios, fetch 등을 통하여 ajax 요청을 하거나, DOM 의 속성을 읽거나 직접 변경하는 작업을 진행합니다.

## 업데이트

- getDerivedStateFromProps
- shouldComponentUpdate
- render
- getSnapshotBeforeUpdate
- componentDidUpdate

### getDerivedStateFromProps

컴포넌트의 `props` 나 `state` 가 바뀌었을때도 이 메서드가 호출!

### shouldComponentUpdate

`shouldComponentUpdate` 메서드는 컴포넌트가 리렌더링 할지 말지를 결정하는 메서드입니다.

```javascript
shouldComponentUpdate(nextProps, nextState) {
    console.log("shouldComponentUpdate", nextProps, nextState);
    // 숫자의 마지막 자리가 4면 리렌더링하지 않습니다
    return nextState.number % 10 !== 4;
}
```
주로 최적화 할 때 사용하는 메서드입니다.  
`React.memo` 의 역할과 비슷 (리렌더링을 방지하여 컴포넌트의 리렌더링 성능 최적화)
★ Chapter1_19

### render

### getSnapshotBeforeUpdate

`getSnapshotBeforeUpdate` 는 **컴포넌트에 변화가 일어나기 직전의 DOM 상태**를 가져와서 특정 값을 반환하면 그 다음 발생하게 되는 `componentDidUpdate` 함수에서 받아와서 사용을 할 수 있습니다.

```javascript
  getSnapshotBeforeUpdate(prevProps, prevState) {
    console.log("getSnapshotBeforeUpdate");
    if (prevProps.color !== this.props.color) {
        // myRef > h1에 연결, 업데이트 되기 전 색상 반환
      return this.myRef.style.color;
    }
    return null;
  }
```

### componentDidUpdate

`componentDidUpdate` 는 리렌더링이 마치고, 화면에 우리가 원하는 변화가 모두 반영되고 난 뒤 호출되는 메서드입니다. 3번째 파라미터로 `getSnapshotBeforeUpdate` 에서 반환한 값을 조회 할 수 있습니다.

```javascript
componentDidUpdate(prevProps, prevState, snapshot) {
    console.log("componentDidUpdate", prevProps, prevState);
    if (snapshot) {
        console.log("업데이트 되기 직전 색상: ", snapshot);
        // snapshot > myRef.style.color
    }
}
```

[getSnapshotBeforeUpdate 업데이트 예제](https://codesandbox.io/s/getsnapshotbeforeupdate-yeje-vpmle?fontsize=14)

## 언마운트

- componentWillUnmount

### componentWillUnmount

`componentWillUnmount` 는 컴포넌트가 화면에서 사라지기 직전에 호출됩니다.

```javascript
componentWillUnmount() {
    console.log("componentWillUnmount");
}
```

여기서는 주로 DOM에 직접 등록했었던 이벤트를 **제거**하고, 만약에 setTimeout 을 걸은것이 있다면 clearTimeout 을 통하여 **제거**를 합니다. 추가적으로, 외부 라이브러리를 사용한게 있고 해당 라이브러리에 dispose 기능이 있다면 여기서 호출!




