[ FastCampus 강의 요약 노트 ]

https://react.vlpt.us/

# 30~31.  componentDidCatch 로 에러 잡아내기 / Sentry 연동

`componentDidCatch` 라는 생명주기 메서드를 사용하여 리액트 애플리케이션에서 발생하는 에러를 처리하는 방법을 알아보도록 하겠습니다!

![에러화면](https://i.imgur.com/iGioIFG.png)
이 화면은 개발환경에서만 보여지는 에러화면이고, 사용자 화면에는 아무것도 나타나지 않는다.

```javascript
import React from 'react';

function User({ user }){
    // error 방지
    if(!user) return null;
    return (
        <div>
            <p><b>ID</b>: {user.id}</p>
            <p><b>Username</b>: {user.name}</p>
        </div>
    )
}

export default User;
import React from 'react';

function User({ user }){
    if(!user) return null;
    return (
        <div>
            <p><b>ID</b>: {user.id}</p>
            <p><b>Username</b>: {user.name}</p>
        </div>
    )
}

export default User;
```
리액트 컴포넌트에서 `null` 을 렌더링하게되면 아무것도 나타나지 않게 됩니다. 이를 **"null checking"** 이라고 부릅니다.

## componentDidCatch로 에러 잡아내기

```javascript
import React, { Component } from 'react';

class ErrorBoundary extends Component{
    state = {
        error : false,
    }

    componentDidCatch(error, info){
        console.log({
            error,
            info
        });

        this.setState({
            error : true
        })
    }

    render(){
        if(this.state.error){
            return <h1>에러 발생</h1>
        }
        // ErrorBoundary 내부의 DOM을 그대로 보여준다.
        return this.props.children;
    }
}

export default ErrorBoundary;
```
여기서 `componentDidCatch` 메서드에는 두개의 파라미터를 사용하게 되는데 
1. 첫번째 파라미터는 에러의 내용 (error)
2. 두번째 파라미터에서는 에러가 발생한 위치 (info)
를 알려준다.

이 메서드에서 현재 컴포넌트 상태 `error` 를 `true` 로 설정을 해주고, `render()` 메서드에서는 만약 `this.state.error` 값이 `true` 라면 에러가 발생했다는 문구를 렌더링하도록 하고 그렇지 않다면 this.props.children 을 렌더링하도록 처리!

## Sentry 연동

[Sentry](sentry.io)

프로젝트 디렉터리에서 `@sentry/browser` 를 설치

```cmd
$ yarn add @sentry/browser
```

아까 Sentry 페이지에서 나타났던 Instruction 에 나타났던 대로 작업을 해주면 된다.

```javascript
//index.js
import * as Sentry from '@sentry/browser';
Sentry.init({dsn: ""});
```

이렇게 에러가 발생 했을 때 Sentry 쪽으로 전달이 되는 것은 개발모드일땐 별도의 작업을 하지 않아도 잘 되지만, 나중에 프로젝트를 완성하여 <u>실제 배포를 하게 됐을 때는 `componentDidCatch` 로 이미 에러를 잡아줬을 경우 Sentry 에게 자동으로 전달이 되지 않습니다.</u>

때문에 ErrorBoundary 에서 다음과 같이 처리를 해주셔야 합니다.

```javascript
//ErrorBoundary.js
import React, { Component } from 'react';
import * as Sentry from '@sentry/browser';

class ErrorBoundary extends Component{
    state = {
        error : false,
    }

    componentDidCatch(error, info){
        console.log({
            error,
            info
        });

        this.setState({
            error : true
        })

        // Sentry 연동
        // NODE_ENV === prodution > 프로덕션 환경
        // development > 개발 환경
        // 프로덕션 환경일 때 Sentry 연동!
        if (process.env.NODE_ENV === 'production') {
            Sentry.captureException(error, { extra: info });
        }
    }

    render(){
        if(this.state.error){
            return <h1>에러 발생</h1>
        }
        return this.props.children;
    }
}

export default ErrorBoundary;
```

## 프러덕션 환경에서 잘 작동하는지 확인 하기

1. 프로젝트 빌드
```cmd
$ yarn build
```
2. 서버 실행
```cmd
$ npx serve ./build
```
serve 는 웹서버를 열어서 특정 디렉터리에 있는 파일을 제공해주는 도구입니다.

이번에는 아까와 달리 에러가 어디서 발생했는지 상세한 정보를 알아보기 쉽지가 않은데요,
이는 빌드 과정에서 코드가 minify 되면서 이름이 c, Xo, Ui, qa 이런식으로 축소됐기 때문입니다.

만약에 코드 위치를 제대로 파악을 하고 싶다면 이 [링크](https://docs.sentry.io/platforms/javascript/sourcemaps/#webpack) 를 참조하시면 됩니다.

`Sentry` 에서 minified 되지 않은 이름을 보려면 `Sourcemap` 이란것을 사용해야 하는데요, 빌드를 할 때마다 자동으로 업로드 되도록 설정 할 수 있고, 직접 업로드 할 수도 있고, 만약에 `Sourcemap` 파일이 공개 되어 있다면 별도의 설정 없이 바로 minified 되지 않은 이름을 볼 수 있습니다.


