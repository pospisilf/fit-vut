CXX ?= g++
CXXFLAGS ?= -Wall -O3
SRCS = *.cpp
TARGET = simulation

$(TARGET): $(SRCS) $(HDRS)
	$(CXX) -std=c++14 $(CXXFLAGS) $(LDFLAGS) $^ -o $@

clean:
	rm -f $(TARGET)

run: $(TARGET)
	./$(TARGET)
