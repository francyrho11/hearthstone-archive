import React from "react";

function Card(props) {

    console.log(props.cardData);

    return (
        <img src={props.cardData.img} />
    );
}

export default Card;