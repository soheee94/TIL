# Flexbox

- [CSS Flexbox 속성 배우기 게임 - Flexbox Froggy](https://flexboxfroggy.com/#ko)

## `flex` 속성을 사용한 크기 조절

플렉스 아이템의 크기를 조절하려면 플렉스 아이템 자체에 규칙 추가

속성

- flex-grow
- flex-shrink
- flex-basis

`flex-basis` 속성은 플렉스 아이템의 너비를 설정할 때 사용
ex) 200px 일 경우 각 플렉스 아이템에 200px 크기의 공간 할당
여유공간을 모든 플렉스 아이템에 균등하게 배분하고 싶다면 `0`으로 설정

`flex-grow` 속성은 플렉스 아이템이 `flex-basis`에 설정한 크기보다 더 커질 수 있는지 설정
ex) 1이라면 플렉스 아이템은 플렉스 컨테이너에 여유 공간이 있을 경우 200px 보다 커진다.

`flex-shrink` 속성은 플렉스 아이템이 `flex-basis`에 설정한 크기보다 작아질 수 있는지 설정
ex) 아이템을 줄 바꿈 하지 않는 500픽셀 너비의 컨테이너 안에 `flex-basis`가 200px 로 설정된 아이템 3개가 있다면, 이 플렉스 아이템들은 `flex-shrink`가 0보다 크지 않으면 컨테이너 영역을 벗어 난다.

### 플렉스 속성 축약 표현

Instead of using `width` (which is a suggestion when using flexbox), you could use `flex: 0 0 230px;` which means:

- `0` = don't grow (shorthand for `flex-grow`)
- `0` = don't shrink (shorthand for `flex-shrink`)
- `230px` = start at `230px` (shorthand for `flex-basis`)
  which means: always be `230px`.

[참고]
새로운 CSS 레이아웃(레이첼 앤드루 지음, webactually)
