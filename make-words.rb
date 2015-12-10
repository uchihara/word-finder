#!/usr/bin/env ruby

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
