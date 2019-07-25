import React from 'react';
import ReactDOM from 'react-dom';

class Tchat extends React.Component {
    state = {
        messages: [],
        newMessage: ""
    }

    componentDidMount() {
        fetch("/tchat/messages")
        .then(res => res.json())
        .then(
            (result) => {
                this.setState({
                    messages: result
                });
            }
        )
    }

    onSubmit = event => {
        event.preventDefault();

        fetch('/tchat/new', {
            method: 'POST',
            body: JSON.stringify({
                    'content': this.state.newMessage,
                     'user': user
                })
        .then(function (response) {
            console.log(response);
            
        })

        // $.ajax({
        //     type: "POST",
        //     url: "/tchat/new",
        //     data: message,
        //     dataType: "json",
        //     success: function (reponse) {
        //         console.log(reponse);
                
        //         this.setState({newMessage: ""})
        //     }
        // });
    }

    onChange = event => {
        this.setState({
            [event.target.name]: event.target.value
        });
    };
      
    render() {
        return (
            <div>
                <h1>Tchat</h1>
                <ul>
                    {this.state.messages.map(message => (
                        <li key={message.id} className="message">
                            {message.user} say : {message.content}
                        </li>
                    ))}
                </ul>
                <form method="post" onSubmit={this.onSubmit}>
                    <input type="hidden"></input>
                    <textarea name="newMessage" value={this.state.newMessage} onChange={this.onChange}></textarea><br></br>
                    <button className="btn btn-primary">Say</button>
                </form>
            </div>
        );
    }
}
var element = document.getElementById('tchat_react');
const user = element.dataset.user;

ReactDOM.render(<Tchat/>,element);