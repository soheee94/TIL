# 카카오 Front End Meet Up
191113   
@D CAMP

## 프렌즈 타임 웹앱 개발기

vue.js > 장점 러닝커브가 낮다  

SPA  
view사이를 자유롭게 이동  
한순간에 트래픽이 몰려들어도 과부하가 적다  
- 초기 로딩이 오래 걸려서 초기 자원의 최적화가 필요

webpack(bundler)  
Dynamic import > promise  
webpack번들 분석 툴  
웹앱에서의 이미지 처리  
한번에 수만의 유저가 몰려들 때 > preloading (비동기)  

자연스러운 애니메이션 처리  
-  이미지가 완전히 로드 되지 않았다면 정지된 이미지로 보여주기
비동기이기 때문에 100프로 이미지 보장 불가..!
- 웹 환경에서의 피할 수 없는 네트워크 지연 문제를 이렇게 해결하기  

ESLint

airbnb 베이스의 일부 커스터마이징

- git pre-commit 을 톨ㅇ해서 ESLint 룰을 통과하지 못하ㅕㄴ 커밋하지못하도록 강제

**Sentry**

클라이언트의 에러도 기록해주는 에러 트래킹 툴

progressive webapp > pwa

## 카카오커머스

angular > 복잡한 폼 쉽게 개발 가능

자주 사용하는 컴포넌트들은 모듈화로 사용

@commerce-ui/dev

퍼스트파티 패키지라 안정성이 좋고 업데이트

서버사이드렌더링은 고려하지 않음

쉐어드 모듈!

## 카카오 vue 리팩토링

vue컴포넌트 테스트 작성 방법

nuxt기반의 vue 컴포넌트

환경 구성

ava, sinon, 

snapshot 테스