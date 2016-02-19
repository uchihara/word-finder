require 'set'

class Dict < Set
  def initialize length
    File.open("data/words-#{length}.txt") do |f|
      super f.read.split(/\n/)
    end
  end

  def exists? word
    include? word
  end
end
