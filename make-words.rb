#!/usr/bin/env ruby

words = []
(ARGV+["data/words.txt"]).each do |file|
  open(file) do |f|
    words += f.read.split("\n")
  end
end

open("data/words.txt", "w") do |f|
  f << words.sort.uniq.join("\n") + "\n"
end

open("data/words.txt") do |f|
  word_counts = Hash.new{|h,k|h[k]=[]}
  while s=f.gets
    s.strip!
    word_counts[s.length] << s
  end

  word_counts.each_pair do |length, words|
    open("data/words-#{length}.txt", "w") do |w|
      w << words.join("\n") + "\n"
    end
  end
end
