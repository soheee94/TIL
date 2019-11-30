[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 1. Sass

Sass (Syntactically Awesome Style Sheets: 문법적으로 짱 멋진 스타일시트) 는 CSS pre-processor 로서, 복잡한 작업을 쉽게 할 수 있게 해주고, 코드의 재활용성을 높여줄 뿐 만 아니라, 코드의 가독성을 높여주어 유지보수를 쉽게해줍니다.

[참고\_velopert blog](https://velopert.com/1712)  
[참고\_가이드라인](https://sass-guidelin.es/ko/0)

Sass 에서는 두가지의 확장자 (.scss/.sass) 를 지원한다.
두 확장자의 문법은 다르다! 확인은 밑에 링크 참고!

[SASS SCSS 차이](https://sass-lang.com/guide)

## 버튼 사이즈 조정하기

```javascript
//Button.js

function Button({ children, size }) {
  return <button className={["Button", size].join(" ")}>{children}</button>;
}
// className 출력 :  Button medium
// or className={`Button ${size}`}

Button.defaultProps = {
  size: "medium"
};
```

조건부로 CSS 클래스를 넣어주고 싶을때 이렇게 문자열을 직접 조합해주는 것 보다 **classnames** 라는 라이브러리를 사용하는 것이 훨씬 편합니다.

[classNames](https://github.com/JedWatson/classnames)를 사용하면 다음과 같이 조건부 스타일링을 할 때 함수의 인자에 문자열, 배열, 객체 등을 전달하여 손쉽게 문자열을 조합 할 수 있습니다.

```javascript
classNames("foo", "bar"); // => 'foo bar'
classNames("foo", { bar: true }); // => 'foo bar'
classNames({ "foo-bar": true }); // => 'foo-bar'
classNames({ "foo-bar": false }); // => ''
classNames({ foo: true }, { bar: true }); // => 'foo bar'
classNames({ foo: true, bar: true }); // => 'foo bar'
classNames(["foo", "bar"]); // => 'foo bar'

// 동시에 여러개의 타입으로 받아올 수 도 있습니다.
classNames("foo", { bar: true, duck: false }, "baz", { quux: true }); // => 'foo bar baz quux'

// false, null, 0, undefined 는 무시됩니다.
classNames(null, false, "bar", undefined, 0, 1, { baz: null }, ""); // => 'bar 1'
```

classnames 설치

```cmd
$ yarn add classnames
```

```javascript
import classNames from "classnames";

function Button({ children, size }) {
  return <button className={classNames("Button", size)}>{children}</button>;
}
```

```scss
// Button.scss
// 사이즈
&.large {
  height: 3rem;
  font-size: 1.25rem;
}

&.medium {
  height: 2.25rem;
  font-size: 1rem;
}

&.small {
  height: 1.75rem;
  font-size: 0.875rem;
}

// 생략
// 붙어있는 컴포넌트에 마진 주기
& + & {
  margin-left: 1rem;
}
```

## 버튼 색상 설정하기

> 개발을 할 때 색상에 대하여 고민이 들 때에는 [open-color](https://yeun.github.io/open-color/ingredients.html) 를 참조

```javascript
// color : blue, gray, pink
function Button({ children, size, color }) {
  return (
    <button className={classNames("Button", size, color)}>{children}</button>
  );
}
```

```scss
// Button.scss
// 색상 관리
&.blue {
  background: $blue;
  &:hover {
    background: lighten($blue, 10%);
  }

  &:active {
    background: darken($blue, 10%);
  }
}

&.gray {
  background: $gray;
  &:hover {
    background: lighten($gray, 10%);
  }

  &:active {
    background: darken($gray, 10%);
  }
}

&.pink {
  background: $pink;
  &:hover {
    background: lighten($pink, 10%);
  }

  &:active {
    background: darken($pink, 10%);
  }
}
```

이렇게 반복이 되는 코드는 Sass 의 [mixin](https://sass-guidelin.es/ko/#mixins) 이라는 기능을 사용하여 쉽게 재사용 할 수 있습니다.

```scss
@mixin button-color($color) {
  background: $color;
  &:hover {
    background: lighten($color, 10%);
  }
  &:active {
    background: darken($color, 10%);
  }
}
```

## outline 옵션 만들기

이번에는 `outline` 이라는 옵션을 주면 버튼에서 테두리만 보여지도록 설정을 해보겠습니다.

```javascript
// Button.js
function Button({ children, size, color, outline }) {
  return (
    <button className={classNames("Button", size, color, { outline })}>
      {children}
    </button>
  );
}
```

```scss
// Button.scss
@mixin button-color($color) {
  background: $color;
  &:hover {
    background: lighten($color, 10%);
  }
  &:active {
    background: darken($color, 10%);
  }
  &.outline {
    color: $color;
    background: none;
    border: 1px solid $color;
    &:hover {
      background: $color;
      color: white;
    }
  }
}
```

```javascript
// App.js
// outline={true} == outline
<div className="buttons">
  <Button size="large" color="blue" outline>
    BUTTON
  </Button>
  <Button color="gray" outline>
    BUTTON
  </Button>
  <Button size="small" color="pink" outline>
    BUTTON
  </Button>
</div>
```

## 전체 너비 차지하는 옵션

이번에는 `fullWidth` 라는 옵션이 있으면 버튼이 전체 너비를 차지하도록 구현을 해보겠습니다.

```javascript
function Button({ children, size, color, outline, fullWidth }) {
  return (
    <button
      className={classNames("Button", size, color, { outline, fullWidth })}
    >
      {children}
    </button>
  );
}
```

```scss
&.fullWidth {
  width: 100%;
  justify-content: center;
  & + & {
    margin-left: 0;
    margin-top: 1rem;
  }
}
```

```javascript
// App.js
<div className="buttons">
  <Button size="large" fullWidth>
    BUTTON
  </Button>
  <Button size="large" fullWidth color="gray">
    BUTTON
  </Button>
  <Button size="large" fullWidth color="pink">
    BUTTON
  </Button>
</div>
```

## ...rest props 전달하기

필요한 이벤트가 있을 때 마다 이벤트를 각각 컴포넌트에 넣어주는건 귀찮습니다. 이러한 문제를 해결 해줄 수 있는 문법이 있는데요! 바로 <u>spread 와 rest</u> 입니다. 이 문법은 주로 배열과 객체, 함수의 파라미터, 인자를 다룰 때 사용하는데, 컴포넌트에서도 사용 할 수 있답니다.

```javascript
// Button.js

function Button({ children, size, color, outline, fullWidth, ...rest }) {
  return (
    <button
      className={classNames("Button", size, color, { outline, fullWidth })}
      {...rest}
    >
      {children}
    </button>
  );
}
```

이렇게 `...rest`를 사용해서 우리가 지정한 `props` 를 제외한 값들을 `rest` 라는 객체에 모아주고, `<button>` 태그에 `{...rest}` 를 해주면, `rest` 안에 있는 객체안에 있는 값들을 모두 `<button>` 태그에 설정을 해준답니다.

그래서 이렇게 컴포넌트가 어떤 `props` 를 받을 지 확실치는 않지만 그대로 다른 컴포넌트 또는 HTML 태그에 전달을 해주어야 하는 상황에는 이렇게 `...rest` 문법을 활용하시면 됩니다!

## className이 겹치지 않게 작성하는 팁!

1. 컴포넌트 이름을 고유하게 지정
2. 최상위 엘리먼트의 클래스 이름을 컴포넌트 이름과 똑같게
3. 그 내부에서 셀렉터 사용
