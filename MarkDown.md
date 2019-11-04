# 마크다운 문법(Syntax)
[출처] FastCampus 강의 (https://heropy.blog/2017/09/30/markdown/)  

## 제목 (Header)

`<h1>` 부터 `<h6>` 까지 제목을 표현할 수 있다
```markdown
# 제목 1
## 제목 2
### 제목 3
#### 제목 4
##### 제목 5
###### 제목 6
```

## 강조 (Emphasis)

각각 `<em>`, `<strong>`, `<del>` 태그로 변환된다.   
밑줄은 `<u></u>`태그를 사용한다.

```markdown
이텔릭체는 *별표(asterisks)* 혹은 _언더바(underscore)_를 사용하세요.
두껍게는 **별표(asterisks)** 혹은 __언더바(underscore)__를 사용하세요.
**_이텔릭체_와 두껍게**를 같이 사용할 수 있습니다.
취소선은 ~~물결표시(tilde)~~를 사용하세요.
<u>밑줄</u>은 `<u></u>`를 사용하세요.
```
이텔릭체는 *별표(asterisks)* 혹은 _언더바(underscore)_를 사용하세요.  
두껍게는 **별표(asterisks)** 혹은 __언더바(underscore)__를 사용하세요.  
**_이텔릭체_와 두껍게**를 같이 사용할 수 있습니다.  
취소선은 ~~물결표시(tilde)~~를 사용하세요.  
<u>밑줄</u>은 `<u></u>`를 사용하세요.

<u style="color:red">CSS Style</u>를 이용해서 색상 변경도 가능하지만 추천하는 방법은 아니다.

## 목록 (List)
`<ol>`, `<ul>` 목록 태그로 변환된다.
```markdown
1. 순서가 필요한 목록
1. 순서가 필요한 목록
  - 순서가 필요하지 않은 목록(서브) 
1. 순서가 필요한 목록
  1. 순서가 필요한 목록(서브)
1. 순서가 필요한 목록

- 순서가 필요하지 않은 목록에 사용 가능한 기호
  - 대쉬(hyphen)
  * 별표(asterisks)
  + 더하기(plus sign)
```
1. 순서가 필요한 목록
1. 순서가 필요한 목록
    - 순서가 필요하지 않은 목록(서브) 
1. 순서가 필요한 목록
    1. 순서가 필요한 목록(서브)
1. 순서가 필요한 목록
- 순서가 필요하지 않은 목록에 사용 가능한 기호
    - 대쉬(hyphen)
    * 별표(asterisks)
    + 더하기(plus sign)

## 링크 (Links)
`<a>` 태그로 변환  
`<a href="">` > `[text](link주소)`
```markdown
[GOOGLE](https://google.com)
[NAVER](https://naver.com "링크 설명(title)을 작성하세요.")
[상대적 참조](../users/login)
```
참조 링크 : `[TEXT][참조 링크 이름]`
```markdown
[Dribbble][Dribbble link]
[GitHub][1]

문서 안에서 [참조 링크]를 그대로 사용할 수도 있습니다.

다음과 같이 문서 내 일반 URL이나 꺾쇠 괄호(`< >`, Angle Brackets)안의 URL은 자동으로 링크를 사용합니다.
구글 홈페이지: https://google.com
네이버 홈페이지: <https://naver.com>

[Dribbble link]: https://dribbble.com
[1]: https://github.com
[참조 링크]: https://naver.com "네이버로 이동합니다!"
```
[GOOGLE](https://google.com)  
[NAVER](https://naver.com "링크 설명(title)을 작성하세요.")  
[상대적 참조](../users/login)  
[Dribbble][Dribbble link]  
[GitHub][1]  

구글 홈페이지: https://google.com  
네이버 홈페이지: <https://naver.com>  

[Dribbble link]: https://dribbble.com
[1]: https://github.com
[참조 링크]: https://naver.com "네이버로 이동합니다!"

a target='blank'는 마크다운에서 적용되지 않기 때문에, 새 창에서 열고 싶다면 HTML A 태그를 이용해서 작성해 주어야 한다!

## 이미지 (Image)

`<img>`로 변환  
링크와 비슷 하지만 앞에 `!`가 붙는다.  
`<img src="주소" alt"대체">` >  `![대체텍스트][주소]`

```markdown
![대체 텍스트(alternative text)를 입력하세요!](http://www.gstatic.com/webp/gallery/5.jpg "링크 설명(title)을 작성하세요.")

★ 링크와 같이 참조링크 제공
![Kayak][logo]  
[logo]: http://www.gstatic.com/webp/gallery/2.jpg "To go kayaking."
```

![대체 텍스트(alternative text)를 입력하세요!](http://www.gstatic.com/webp/gallery/5.jpg "링크 설명(title)을 작성하세요.") 

![Kayak][logo]

[logo]:http://www.gstatic.com/webp/gallery/2.jpg "To go kayaking."

### 이미지에 링크
```markdown
[![Vue](/images/vue.png)](https://kr.vuejs.org/)
```
[![Vue](/images/vue.png)](https://kr.vuejs.org/)


## 코드(Code) 강조
`<pre>`, `<code>` 로 변환  
Grave 키(`)를 입력

### 인라인 코드 강조
```markdown
`background`혹은 `background-image` 속성으로 요소에 배경 이미지를 삽입할 수 있습니다.
```
`background`혹은 `background-image` 속성으로 요소에 배경 이미지를 삽입할 수 있습니다.

### 블록 코드 강조
`를 3번 입력하고 코드 종류도 적는다.
```markdown
    ```html
    <a href="https://www.google.co.kr/" target="_blank">GOOGLE</a>
    ```

    ```css
    .list > li {
    position: absolute;
    top: 40px;
    }
    ```

    ```javascript
    function func() {
    var a = 'AAA';
    return a;
    }
    ```

    ```bash
    $ vim ./~zshrc
    ```

    ```python
    s = "Python syntax highlighting"
    print s
    ```

    ```
    No language indicated, so no syntax highlighting. 
    But let's throw in a tag.
    ```
```


## 표 (Table)
`<table>` 태그로 변환 (header와 body의 구분이 필요하다)  
헤더 셀을 구분할 때 3개 이상의 -(hyphen/dash) 기호가 필요합니다.  
헤더 셀을 구분하면서 :(Colons) 기호로 셀(열/칸) 안에 내용을 정렬할 수 있습니다.  
가장 좌측과 가장 우측에 있는 |(vertical bar) 기호는 생략 가능합니다.

```markdown
| 값 | 의미 | 기본값 |
|---|:---:|---:|
| `static` | 유형(기준) 없음 / 배치 불가능 | `static` |
| `relative` | 요소 자신을 기준으로 배치 |  |
| `absolute` | 위치 상 부모(조상)요소를 기준으로 배치 |  |
| `fixed` | 브라우저 창을 기준으로 배치 |  |

↓ 
같은 값으로 출력된다.
맨 앞과 뒤의 vertical bar는 생략 가능
:가 없으면 왼쪽 정렬 :---:는 중앙정렬, ---:는 오른쪽 정렬
---는 --만 써도 같은 값 출력 가능

값 | 의미 | 기본값
---|:---:|---:
`static` | 유형(기준) 없음 / 배치 불가능 | `static`
`relative` | 요소 **자신**을 기준으로 배치 |
`absolute` | 위치 상 **_부모_(조상)요소**를 기준으로 배치 |
`fixed` | **브라우저 창**을 기준으로 배치 |
```

값 | 의미 | 기본값
---|:---:|---:
`static` | 유형(기준) 없음 / 배치 불가능 | `static`
`relative` | 요소 **자신**을 기준으로 배치 |
`absolute` | 위치 상 **_부모_(조상)요소**를 기준으로 배치 |
`fixed` | **브라우저 창**을 기준으로 배치 |


## 인용문 (BlockQuote)

`<blockquote>` 태그로 변환
```markdown
> 남의 말이나 글에서 직접 또는 간접으로 따온 문장.
> _(네이버 국어 사전)_ > 두번째 줄 인용문 삽입

> 인용문을 작성하세요!
>> 중첩된 인용문(nested blockquote)을 만들 수 있습니다.
>>> 중중첩된 인용문 1
>>> 중중첩된 인용문 2
>>> 중중첩된 인용문 3
```
> 남의 말이나 글에서 직접 또는 간접으로 따온 문장.
> _(네이버 국어 사전)_

> 인용문을 작성하세요!
>> 중첩된 인용문(nested blockquote)을 만들 수 있습니다.
>>> 중중첩된 인용문 1
>>> 중중첩된 인용문 2
>>> 중중첩된 인용문 3


### 원시 HTML (Raw HTML)
마크다운 문법이 아닌 원시 HTML 문법을 사용할 수 있습니다.  
마크다운에서 지원하지 않는 기능을 사용할 때 유용하고 대부분 잘 동작한다.

```markdown
<img width="150" src="http://www.gstatic.com/webp/gallery/4.jpg" alt="Prunus" title="A Wild Cherry (Prunus avium) in flower">

![Prunus](http://www.gstatic.com/webp/gallery/4.jpg)
```
<img width="150" src="http://www.gstatic.com/webp/gallery/4.jpg" alt="Prunus" title="A Wild Cherry (Prunus avium) in flower">

![Prunus](http://www.gstatic.com/webp/gallery/4.jpg)

## 수평선 (Horizontal Line)
`<hr>`태그로 변환
```markdown
---
(Hyphens)

***
(Asterisks)

___
(Underscores)
```

---

## 줄바꿈 (Line Break)
```markdown
동해물과 백두산이 마르고 닳도록 
하느님이 보우하사 우리나라 만세   <!--띄어쓰기 2번-->
무궁화 삼천리 화려 강산<br>
대한 사람 대한으로 길이 보전하세
```