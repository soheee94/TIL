[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 10. redux-saga

## 소개

redux-saga는 redux-thunk 다음으로 가장 많이 사용되는 라이브러리입니다.

- redux-thunk : 함수를 디스패치 할 수 있게 해주는 미들웨어
- redux-saga : 액션을 모니터링하고 있다가, 특정 액션이 발생하면 이에 따라 특정 작업을 하는 방식으로 사용

redux-saga는 redux-thunk로 못하는 다양한 작업들을 처리 할 수 있습니다.

1. 비동기 작업을 할 때 기존 요청을 취소 처리 할 수 있습니다
2. 특정 액션이 발생했을 때 이에 따라 다른 액션이 디스패치되게끔 하거나, 자바스크립트 코드를 실행 할 수 있습니다.
3. 웹소켓을 사용하는 경우 Channel 이라는 기능을 사용하여 더욱 효율적으로 코드를 관리 할 수 있습니다 (참고)
4. API 요청이 실패했을 때 재요청하는 작업을 할 수 있습니다.

이 외에도 다양한 까다로운 비동기 작업들을 redux-saga를 사용하여 처리 할 수 있답니다.

redux-saga는 다양한 상황에 쓸 수 있는 만큼, 제공되는 기능도 많고, 사용방법도 진입장벽이 꽤나 큽니다. 자바스크립트 초심자라면 생소할만한 **Generator** 문법을 사용하기 때문이다!

## Generator 문법 배우기

제너레이터 함수를 사용하면 함수에서 값을 순!차!적!으로 반환할 수 있습니다. 함수의 흐름을 도중에 멈춰놓았다가 나중에 이어서 진행 할 수도 있습니다

```javascript
function* generatorFunction() {
  console.log("안녕하세요?");
  yield 1;
  console.log("제너레이터 함수");
  yield 2;
  console.log("function*");
  yield 3;
  return 4;
}
```

제너레이터 함수를 만들 때에는 `function*` 이라는 키워드를 사용합니다.

제너레이터 함수를 호출했을때는 한 객체가 반환되는데요, 이를 제너레이터라고 부릅니다.

함수를 작성한 뒤에는 다음 코드를 사용해 제너레이터를 생성해보세요.

```javascript
const generator = generatorFunction();
```

제너레이터 함수를 호출한다고 해서 해당 함수 안의 코드가 바로 시작되지는 않습니다. `generator.next()` 를 호출해야만 코드가 실행되며, `yield`를 한 값을 반환하고 코드의 흐름을 멈춥니다.

코드의 흐름이 멈추고 나서 `generator.next()` 를 다시 호출하면 흐름이 이어서 다시 시작됩니다.

![generator result gif](https://i.imgur.com/wkAaazv.gif)

또 다른 예시

```javascript
function* sumGenerator() {
  console.log("sumGenerator이 시작됐습니다.");
  let a = yield;
  console.log("a값을 받았습니다.");
  let b = yield;
  console.log("b값을 받았습니다.");
  yield a + b;
}
```

![generator result gif](https://i.imgur.com/ruuoSJN.gif)
