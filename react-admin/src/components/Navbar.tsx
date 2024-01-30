import styled from "styled-components";

const Container = styled.nav`
    display:flex;
    background-color:#d3d3d3;
    height:90px;
    width:auto;
    border: 1px solid black;
    flex-grow:1;
   
    
`



 const Navbar = () => {

    return(
        <>
        <Container />
    </>
    )
    
}

export default Navbar;