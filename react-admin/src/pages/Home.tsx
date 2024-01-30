import Navbar from "../components/Navbar";
import Menu from "../components/Menu";
import styled from "styled-components";

const Main = styled.main`
  background-color: #d3d3d3;
  height:100vh;
  display:flex;
  overflow-y:auto;
`;

export const Home = () => {
  return (
    <>
      <Main>
        <Menu />
        <Navbar />
      </Main>
    </>
  );
};