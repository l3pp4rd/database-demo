package main

import (
	"encoding/json"
	"flag"
	"fmt"
	"net/http"
	"time"
)

var concurrency = flag.Int("concurrency", 10, "cuncurrency rate to execute given url")
var iterations = flag.Int("iterations", 100, "iterations to execute")

func main() {
	start := time.Now()
	defer func() {
		fmt.Println("took:", time.Since(start))
	}()
	flag.Parse()

	uri := flag.Arg(0)
	buffer := make(chan int, *concurrency)
	for i := 0; i < *iterations; i++ {
		buffer <- i
		go request(uri, buffer)
	}

	// wait until all are processed
	for i := 0; i < *concurrency; i++ {
		buffer <- i
	}
	close(buffer)
}

func request(uri string, buffer <-chan int) {
	req, _ := http.NewRequest("GET", uri, nil)
	resp, _ := http.DefaultClient.Do(req)
	defer resp.Body.Close()

	type Fail struct {
		Error struct {
			Message string
			Code    int
		}
	}

	if resp.StatusCode != 200 {
		var fail Fail
		json.NewDecoder(resp.Body).Decode(&fail)
		fmt.Printf("failure encountered: %d - %s\n", resp.StatusCode, fail.Error.Message)
	}
	<-buffer
}
