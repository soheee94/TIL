[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 2. CSS Module

리액트 프로젝트에서 컴포넌트를 스타일링 할 때 **CSS Module** 이라는 기술을 사용하면, <mark>CSS 클래스가 중첩되는 것을 완벽히 방지할 수 있습니다.</mark>

CRA 로 만든 프로젝트에서 CSS Module 를 사용 할 때에는, CSS 파일의 확장자를 `.module.css` 로 하면 되는데요, 예를 들어서 다음과 같이 `Box.module.css` 라는 파일을 만들게 된다면

리액트 컴포넌트 파일에서 해당 CSS 파일을 불러올 때 CSS 파일에 선언한 클래스 이름들이 모두 고유해집니다. 고유 CSS 클래스 이름이 만들어지는 과정에서는 파일 경로, 파일 이름, 클래스 이름, 해쉬값 등이 사용 될 수 있습니다.

```javascript
//Box.js
import React from "react";
import styles from "./Box.module.css";

function Box() {
  return <div className={styles.Box}>{styles.Box}</div>;
}

export default Box;
```

`className` 을 설정 할 때에는 `styles.Box` 이렇게 `import로` 불러온 `styles` 객체 안에 있는 값을 참조해야 합니다

클래스 이름에 대하여 고유한 이름들이 만들어지기 때문에, 실수로 CSS 클래스 이름이 다른 관계 없는 곳에서 사용한 CSS 클래스 이름과 중복되는 일에 대하여 걱정 할 필요가 없습니다.

이 기술은 다음과 같은 상황에 사용하면 유용합니다.

- 레거시 프로젝트에 리액트를 도입할 때 (기존 프로젝트에 있던 CSS 클래스와 이름이 중복되어도 스타일이 꼬이지 않게 해줍니다.)
- CSS 클래스를 중복되지 않게 작성하기 위하여 CSS 클래스 네이밍 규칙을 만들기 귀찮을 때

CSS Module 별도로 설치해야 할 라이브러리는 없습니다. 이 기능은 `webpack` 에서 사용하는 `css-loader` 에서 지원되는데, CRA 로 만든 프로젝트에는 이미 적용이 되어있으니 바로 사용하면 됩니다.

```javascript
// CheckBox.js
function CheckBox({ checked, children, ...rest }) {
  return (
    <div>
      <label>
        <input type="checkbox" checked={checked} {...rest} />
        <div>{checked ? "체크됨" : "체크안됨"}</div>
      </label>
      <span>{children}</span>
    </div>
  );
}

export default CheckBox;
```

```javascript
// App.js
import React, { useState } from "react";
import CheckBox from "./component/CheckBox";

function App() {
  const [check, setCheck] = useState(false);
  const onChange = e => {
    setCheck(e.target.checked);
  };
  return (
    <div>
      <CheckBox onChange={onChange} checked={check}></CheckBox>
      <p>
        <b>check : </b>
        {check ? "true" : "false"}
      </p>
    </div>
  );
}

export default App;
```

스타일링 [react-icons](https://react-icons.netlify.com/#/) 설치

```cmd
$ yarn add react-icons
```

이 라이브러리를 사용하면 Font Awesome, Ionicons, Material Design Icons, 등의 아이콘들을 컴포넌트 형태로 쉽게 사용 할 수 있습니다. 해당 라이브러리의 문서 를 열으셔서 원하는 아이콘들을 불러와서 사용하시면 되는데요, 우리는 Material Design Icons 의 MdCheckBox, MdCheckBoxOutline 을 사용하겠습니다.

```javascript
// Checkbox.js
import React from "react";
import { MdCheckBox, MdCheckBoxOutlineBlank } from "react-icons/md";
import styles from "./CheckBox.module.css";

function CheckBox({ checked, children, ...rest }) {
  return (
    <div className={styles.checkbox}>
      <label>
        <input type="checkbox" checked={checked} {...rest} />
        <div className={styles.icon}>
          {checked ? (
            <MdCheckBox className={styles.checked} />
          ) : (
            <MdCheckBoxOutlineBlank />
          )}
        </div>
      </label>
      <span>{children}</span>
    </div>
  );
}

export default CheckBox;
```

개발자 도구로 엘리먼트를 선택해보시면 다음과 같이 고유한 클래스 이름이 만들어진 것을 확인 할 수 있습니다.

```HTML
<div class="CheckBox_icon__1J1vh"></div>
```

만약 클래스 이름에 - 가 들어가 있다면 다음과 같이 사용해야합니다: styles['my-class']

그리고, 만약에 여러개가 있다면 다음과 같이 작성해합니다: ${styles.one} ${styles.two}

조건부 스타일링을 해야 한다면 더더욱 번거롭겠지요? ${styles.one} ${condition ? styles.two : ''}

우리가 이전 섹션에서 Sass 를 배울 때 썼었던 `classnames` 라이브러리에는 `bind` 기능이 있는데요, 이 기능을 사용하면 `CSS Module` 을 조금 더 편하게 사용 할 수 있습니다.

```javascript
//checkbox.js
import classNames from "classnames/bind";

const cx = classNames.bind(styles);

function CheckBox({ checked, children, ...rest }) {
  return (
    <div className={cx(`checkbox`)}>
      <label>
        <input type="checkbox" checked={checked} {...rest} />
        <div className={cx(`icon`)}>
          {checked ? (
            <MdCheckBox className={cx(`checked`)} />
          ) : (
            <MdCheckBoxOutlineBlank />
          )}
        </div>
      </label>
      <span>{children}</span>
    </div>
  );
}
```

`classnames` 의 `bind` 기능을 사용하면, CSS 클래스 이름을 지정해 줄 때 `cx('클래스이름')` 과 같은 형식으로 편하게 사용 할 수 있습니다. 여러개의 CSS 클래스를 사용해야하거나, 조건부 스타일링을 해야 한다면 더더욱 편하겠지요?

```javascript
cx("one", "two");
cx("my-component", {
  condition: true
});
cx("my-component", ["another", "classnames"]);
```

## 기타 내용

CSS Module 은 Sass 에서도 사용 할 수 있습니다. 그냥 확장자를 .module.scss 로 바꿔주면 됩니다.

CSS Module 을 사용하고 있는 파일에서 클래스 이름을 고유화 하지 않고 전역적 클래스이름을 사용하고 싶다면 다음과 작성하면 됩니다.

```css
:global .my-global-name {
}
```

반대로, CSS Module 을 사용하지 않는 곳에서 특정 클래스에서만 고유 이름을 만들어서 사용하고 싶다면 다음과 같이 할 수 있습니다.

```css
:local .make-this-local {
}
```

이 기술은 레거시 프로젝트에 리액트를 도입하게 될 때, 또는 클래스 이름 짓는 규칙을 정하기 힘든 상황이거나 번거로울 때 사용하면 편합니다.
