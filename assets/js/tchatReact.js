import React from 'react';
import ReactDOM from 'react-dom';

class Tchat extends React.Component {
    state = {
        message: "Merde, c'est un super message"
    }
    render() {
        return (
            <div>
                <h1>Tchat</h1>
                <p>{this.state.message}</p>
            </div>
        );
    }
}

ReactDOM.render(<Tchat/>,document.getElementById('tchat_react'));