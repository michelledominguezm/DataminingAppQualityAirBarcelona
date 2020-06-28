import React from 'react';

const Fetcher = (endpoint, query) => {
  
  return fetch('http://dataminingapp.test/api' + endpoint)
          .then((response) => response.json())
            .then((response_json) => {
                  return response_json;
            })
}

  export default Fetcher;