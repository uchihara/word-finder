class Dict
  attr_reader :dict

  def initialize length
    @dict = Set.new
    File.open("data/words-#{length}.txt") do |f|
      @dict = Set[*f.read.split(/\n/)]
    end
  end

  def exists? word
    @dict.include? word
  end
end
